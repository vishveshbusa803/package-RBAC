<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthSetting;
use App\Models\User;
use App\Models\UserTwoFactor;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/otp-verify';
    protected $google2fa;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->google2fa = new Google2FA();
    }

    /* ================= CAPTCHA ================= */
    public function generateCaptcha()
    {
        $captchaEnabled = $this->isAuthEnabled('CAPTCHA');
        if (!$captchaEnabled) {
            $img = imagecreate(120, 40);
            imagecolorallocate($img, 255, 255, 255);
            header('Content-Type: image/png');
            imagepng($img);
            imagedestroy($img);
            return;
        }

        $code = strtoupper(Str::random(5));
        session(['captcha' => $code]);

        $img = imagecreate(120, 40);
        imagecolorallocate($img, 255, 255, 255);
        $txt = imagecolorallocate($img, 60, 60, 60);

        for ($i = 0; $i < 80; $i++) {
            imagesetpixel(
                $img,
                rand(0, 120),
                rand(0, 40),
                imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255))
            );
        }

        for ($i = 0; $i < strlen($code); $i++) {
            imagestring($img, 5, 20 + ($i * 18), 10, $code[$i], $txt);
        }

        header('Content-Type: image/png');
        imagepng($img);
        imagedestroy($img);
    }

    /* ================= LOGIN VALIDATION ================= */
    protected function validateLogin(Request $request)
    {
        $captchaEnabled = $this->isAuthEnabled('CAPTCHA');
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($captchaEnabled) {
            $rules['captcha'] = 'required|string';
        }

        $request->validate($rules);

        if ($captchaEnabled && strtolower($request->captcha) !== strtolower(session('captcha'))) {
            throw ValidationException::withMessages([
                'captcha' => ['Invalid captcha']
            ]);
        }
    }

    /* ================= AFTER LOGIN ================= */
    protected function authenticated(Request $request, $user)
    {
        // Store user ID for later login
        session(['otp_user_id' => $user->id, 'otp_user_email' => $user->Email]);

        // Get email settings
        $emailSetting = $this->isAuthEnabled('EMAIL_VERIFY');

        if ($emailSetting) {
            // Store static Email OTP
            session([
                'email_otp' => '111111', // Static OTP for email
                'email_otp_attempts' => 0,
                'email_otp_sent_at' => now(),
                'email_otp_expires_at' => now()->addMinutes(10),
                'email_resend_available_at' => now()->addSeconds(30),
            ]);

            Session::flash('toast', [
                'type' => 'success',
                'message' => 'OTP sent to your email successfully!'
            ]);
        }

        // Store static Mobile OTP
        session(['mobile_otp' => '222222']); // Static OTP for mobile

        // Check if user has 2FA setup using emp_id
        $twoFactorRecord = UserTwoFactor::where('emp_id', $user->id)->first();
        
        if ($twoFactorRecord && $twoFactorRecord->is_enabled) {
            // User has 2FA enabled
            session(['twofa_secret' => $twoFactorRecord->secret_key]);
        }

        Auth::logout();
        return $this->startVerificationFlow();
    }

    /* ================= EMAIL OTP ================= */
    public function showOtpForm()
    {
        $emailSetting = $this->isAuthEnabled('EMAIL_VERIFY');
        
        if (!$emailSetting) {
            return $this->checkAndRedirectToNextStep();
        }

        // Get user email
        $userId = session('otp_user_id');
        $user = User::find($userId);
        $maskedEmail = $user ? $this->maskEmail($user->Email) : 'your email';

        // Check if resend is available
        $canResend = true;
        $secondsLeft = 0;
        
        if (session('email_resend_available_at') && now()->lt(session('email_resend_available_at'))) {
            $canResend = false;
            $secondsLeft = now()->diffInSeconds(session('email_resend_available_at'));
        }

        // Get attempts from session or set default
        $attempts = session('email_otp_attempts', 0);
        $attemptsLeft = 3 - $attempts; // Default to 3 attempts if not set

        // Get expiry time
        $expiresInSeconds = 0;
        if (session('email_otp_expires_at')) {
            $expiresInSeconds = max(0, now()->diffInSeconds(session('email_otp_expires_at')));
        }

        // Get reset time (default 30 seconds)
        $otpResetTime = 30;

        return view('auth.otp-verify', [
            'email' => $maskedEmail,
            'canResend' => $canResend,
            'secondsLeft' => $secondsLeft,
            'attemptsLeft' => $attemptsLeft,
            'expiresInSeconds' => $expiresInSeconds,
            'otpResetTime' => $otpResetTime,
            'emailSetting' => $emailSetting,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $emailSetting = $this->isAuthEnabled('EMAIL_VERIFY');

        if (!$emailSetting) {
            return $this->checkAndRedirectToNextStep();
        }

        $request->validate(['otp' => 'required|digits:6']);

        // Check OTP expiration
        if (now()->gt(session('email_otp_expires_at'))) {
            Session::flash('toast', [
                'type' => 'error',
                'message' => 'OTP has expired. Please request a new one.'
            ]);
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Get current attempts
        $attempts = session('email_otp_attempts', 0);
        
        // Check if maximum attempts reached (default 3)
        if ($attempts >= 3) {
            Session::flash('toast', [
                'type' => 'error',
                'message' => 'Maximum attempts reached. Please login again.'
            ]);
            return $this->redirectToLogin();
        }

        // Verify OTP - Use static OTP 111111
        if ($request->otp !== '111111') {
            // Increment attempts
            $attempts++;
            session(['email_otp_attempts' => $attempts]);
            
            $attemptsLeft = 3 - $attempts;

            if ($attemptsLeft <= 0) {
                Session::flash('toast', [
                    'type' => 'error',
                    'message' => 'Maximum attempts reached. Please login again.'
                ]);
                return $this->redirectToLogin();
            }

            Session::flash('toast', [
                'type' => 'error',
                'message' => "Invalid OTP. {$attemptsLeft} attempts left."
            ]);
            return back()->withErrors(['otp' => "Invalid OTP. {$attemptsLeft} attempts left."]);
        }

        // OTP verified successfully
        session(['email_verified' => true]);
        session()->forget(['email_otp', 'email_otp_attempts', 'email_otp_expires_at']);

        Session::flash('toast', [
            'type' => 'success',
            'message' => 'Email verified successfully!'
        ]);

        return $this->checkAndRedirectToNextStep();
    }

    public function resendOtp(Request $request)
    {
        $emailSetting = $this->isAuthEnabled('EMAIL_VERIFY');
        
        if (!$emailSetting) {
            return response()->json([
                'success' => false,
                'message' => 'Email OTP is not enabled'
            ]);
        }

        // Check resend timeout
        if (session('email_resend_available_at') && now()->lt(session('email_resend_available_at'))) {
            $secondsLeft = now()->diffInSeconds(session('email_resend_available_at'));
            return response()->json([
                'success' => false,
                'message' => "Please wait before requesting a new OTP",
                'seconds_left' => $secondsLeft
            ]);
        }

        // Get user
        $userId = session('otp_user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'redirect' => true
            ]);
        }

        // Check if attempts exhausted
        $attempts = session('email_otp_attempts', 0);
        if ($attempts >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum attempts reached. Please login again.',
                'redirect' => true
            ]);
        }

        // Update session with static OTP
        session([
            'email_otp' => '111111', // Static OTP
            'email_otp_attempts' => 0, // Reset attempts on resend
            'email_otp_sent_at' => now(),
            'email_otp_expires_at' => now()->addMinutes(10),
            'email_resend_available_at' => now()->addSeconds(30),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New OTP sent successfully',
            'seconds_left' => 30,
            'expires_in' => 10 * 60, // 10 minutes in seconds
        ]);
    }

    /* ================= REDIRECT TO LOGIN ================= */
    private function redirectToLogin()
    {
        session()->forget([
            'otp_user_id',
            'otp_user_email',
            'email_otp',
            'mobile_otp',
            'email_verified',
            'mobile_verified',
            'twofa_verified',
            'email_otp_attempts',
            'email_otp_expires_at',
            'email_resend_available_at',
            'twofa_secret',
            'twofa_qr_code',
            'is_first_time_2fa',
            'show_verify_screen'
        ]);

        return redirect()->route('login');
    }

    /* ================= MOBILE OTP ================= */
    public function showMobileOtpForm()
    {
        $mobileOtpEnabled = $this->isAuthEnabled('MOBILE_VERIFY');

        if (!$mobileOtpEnabled) {
            return $this->checkAndRedirectToNextStep();
        }

        $emailOtpEnabled = $this->isAuthEnabled('EMAIL_VERIFY');
        if ($emailOtpEnabled && !session('email_verified')) {
            return redirect()->route('otp.form');
        }

        return view('auth.mobile-otp-verify');
    }

    public function verifyMobileOtp(Request $request)
    {
        $mobileOtpEnabled = $this->isAuthEnabled('MOBILE_VERIFY');

        if (!$mobileOtpEnabled) {
            return $this->checkAndRedirectToNextStep();
        }

        $request->validate(['otp' => 'required|digits:6']);

        // Verify Mobile OTP - Use static OTP 222222
        if ($request->otp !== '222222') {
            Session::flash('toast', [
                'type' => 'error',
                'message' => 'Invalid Mobile OTP'
            ]);
            return back()->withErrors(['otp' => 'Invalid Mobile OTP']);
        }

        Session::flash('toast', [
            'type' => 'success',
            'message' => 'Mobile verified successfully!'
        ]);

        session(['mobile_verified' => true]);
        return $this->checkAndRedirectToNextStep();
    }

    /* ================= TWO FACTOR AUTHENTICATION ================= */
    public function showTwoFactorForm()
    {
        $twoFaEnabled = $this->isAuthEnabled('TWO_FACTOR');

        if (!$twoFaEnabled) {
            return $this->completeLogin();
        }

        $emailOtpEnabled = $this->isAuthEnabled('EMAIL_VERIFY');
        $mobileOtpEnabled = $this->isAuthEnabled('MOBILE_VERIFY');

        if ($emailOtpEnabled && !session('email_verified')) {
            return redirect()->route('otp.form');
        }

        if ($mobileOtpEnabled && !session('mobile_verified')) {
            return redirect()->route('mobile.otp.form');
        }

        // Get user details
        $userId = session('otp_user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return $this->redirectToLogin();
        }

        // Check if we should show verify screen directly
        if (session('show_verify_screen')) {
            session()->forget('show_verify_screen');
            return $this->showVerifyScreen($user);
        }

        // Check if user has existing 2FA setup - USING emp_id instead of user_id
        $twoFactorRecord = UserTwoFactor::where('emp_id', $user->id)->first();
        
        if ($twoFactorRecord && $twoFactorRecord->is_enabled == 1) {
            // User has existing 2FA setup, show verification only
            Log::info('2FA Found for user ID: ' . $user->id . ', emp_id: ' . $user->id);
            return $this->showVerifyScreen($user);
        } else {
            // First time setup - generate new secret and QR code
            Log::info('No 2FA found for user ID: ' . $user->id . ', showing setup screen');
            return $this->showSetupScreen($user);
        }
    }

    private function showSetupScreen($user)
    {
        // Generate new secret key
        $secret = $this->google2fa->generateSecretKey();
        session(['twofa_secret' => $secret]);
        
        // Generate QR Code URL
        $qrCodeUrl = $this->generateQRCode($user, $secret);
        
        return view('auth.two-factor-setup', [
            'user' => $user,
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    private function showVerifyScreen($user)
    {
        // Get secret from session or database
        $secret = session('twofa_secret');
        
        if (!$secret) {
            $twoFactorRecord = UserTwoFactor::where('emp_id', $user->id)->first();
            if ($twoFactorRecord && $twoFactorRecord->secret_key) {
                $secret = $twoFactorRecord->secret_key;
                session(['twofa_secret' => $secret]);
            } else {
                // Generate new secret if not found
                $secret = $this->google2fa->generateSecretKey();
                session(['twofa_secret' => $secret]);
            }
        }

        return view('auth.two-factor-verify', [
            'user' => $user,
        ]);
    }

    // Method to show verify screen after clicking "Proceed to Verification"
    public function showTwoFactorVerify()
    {
        $userId = session('otp_user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return $this->redirectToLogin();
        }

        // Set flag to show verify screen
        session(['show_verify_screen' => true]);
        
        return $this->showVerifyScreen($user);
    }

    public function verifyTwoFactor(Request $request)
    {
        $twoFaEnabled = $this->isAuthEnabled('TWO_FACTOR');

        if (!$twoFaEnabled) {
            return $this->completeLogin();
        }

        $request->validate(['code' => 'required|digits:6']);

        // Get user
        $userId = session('otp_user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return $this->redirectToLogin();
        }

        // Get secret from session
        $secret = session('twofa_secret');
        
        if (!$secret) {
            // Try to get from database - USING emp_id
            $twoFactorRecord = UserTwoFactor::where('emp_id', $user->id)->first();
            if ($twoFactorRecord && $twoFactorRecord->secret_key) {
                $secret = $twoFactorRecord->secret_key;
                session(['twofa_secret' => $secret]);
            } else {
                Session::flash('toast', [
                    'type' => 'error',
                    'message' => '2FA setup error. Please login again.'
                ]);
                return $this->redirectToLogin();
            }
        }

        // Verify the code - Use static 2FA code 333333
        if ($request->code !== '333333') {
            Session::flash('toast', [
                'type' => 'error',
                'message' => 'Invalid authentication code. Please try again.'
            ]);
            return back()->withErrors(['code' => 'Invalid authentication code']);
        }

        // Check if this is first time setup (no record in database)
        $twoFactorRecord = UserTwoFactor::where('emp_id', $user->id)->first();
        
        if (!$twoFactorRecord || $twoFactorRecord->is_enabled != 1) {
            // First time setup - save to database USING emp_id
            UserTwoFactor::updateOrCreate(
                ['emp_id' => $user->id],
                [
                    'secret_key' => $secret,
                    'is_enabled' => 1,  // Using 1 instead of true for integer
                    'is_active' => 1,   // Using 1 instead of true
                ]
            );
            
            Session::flash('toast', [
                'type' => 'success',
                'message' => 'Two-factor authentication has been set up successfully!'
            ]);
        } else {
            Session::flash('toast', [
                'type' => 'success',
                'message' => 'Two-factor authentication verified successfully!'
            ]);
        }

        // Mark 2FA as verified
        session(['twofa_verified' => true]);
        return $this->completeLogin();
    }

    /* ================= HELPER METHODS ================= */

    private function isAuthEnabled($authCode)
    {
        return AuthSetting::where('AuthCode', $authCode)
            ->where('IsEnabled', 1)
            ->where('IsActive', 1)
            ->exists();
    }

    private function generateQRCode($user, $secret)
    {
        try {
            // Generate Google Authenticator URL
            $g2faUrl = $this->google2fa->getQRCodeUrl(
                config('app.name', 'My Application'),
                $user->Email,
                $secret
            );

            // Use external QR code service
            $appName = rawurlencode(config('app.name', 'My Application'));
            $email = rawurlencode($user->Email);
            
            return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . 
                   urlencode("otpauth://totp/{$appName}:{$email}?secret={$secret}&issuer={$appName}");
            
        } catch (\Exception $e) {
            Log::error('QR Code Generation Error: ' . $e->getMessage());
            
            // Fallback to simple QR code with secret
            return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . 
                   urlencode($secret);
        }
    }

    private function maskEmail($email)
    {
        if (!$email) return 'your email';
        
        $parts = explode('@', $email);
        if (count($parts) !== 2) return $email;

        $username = $parts[0];
        $domain = $parts[1];

        $maskedUsername = substr($username, 0, 2) . str_repeat('*', max(0, strlen($username) - 4)) . substr($username, -2);

        $domainParts = explode('.', $domain);
        if (count($domainParts) >= 2) {
            $maskedDomain = substr($domainParts[0], 0, 2) . str_repeat('*', max(0, strlen($domainParts[0]) - 4)) . '.' . $domainParts[1];
        } else {
            $maskedDomain = $domain;
        }

        return $maskedUsername . '@' . $maskedDomain;
    }

    private function startVerificationFlow()
    {
        if ($this->isAuthEnabled('EMAIL_VERIFY')) {
            return redirect()->route('otp.form');
        } elseif ($this->isAuthEnabled('MOBILE_VERIFY')) {
            return redirect()->route('mobile.otp.form');
        } elseif ($this->isAuthEnabled('TWO_FACTOR')) {
            return redirect()->route('twofa.form');
        } else {
            return $this->completeLogin();
        }
    }

    private function checkAndRedirectToNextStep()
    {
        if ($this->isAuthEnabled('MOBILE_VERIFY') && !session('mobile_verified')) {
            return redirect()->route('mobile.otp.form');
        }

        if ($this->isAuthEnabled('TWO_FACTOR') && !session('twofa_verified')) {
            return redirect()->route('twofa.form');
        }

        return $this->completeLogin();
    }

    private function completeLogin()
    {
        $userId = session('otp_user_id');

        if ($userId) {
            Auth::loginUsingId($userId);

            // Clear all session data
            session()->forget([
                'otp_user_id',
                'otp_user_email',
                'email_otp',
                'mobile_otp',
                'email_verified',
                'mobile_verified',
                'twofa_verified',
                'email_otp_attempts',
                'email_otp_expires_at',
                'email_resend_available_at',
                'twofa_secret',
                'twofa_qr_code',
                'is_first_time_2fa',
                'show_verify_screen'
            ]);

            Session::flash('toast', [
                'type' => 'success',
                'message' => 'Login successful!'
            ]);

            return redirect()->route('home');
        }

        return $this->redirectToLogin();
    }
}