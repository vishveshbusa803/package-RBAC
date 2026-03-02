<?php

namespace Param\RBAC\Http\Controllers;

use Illuminate\Http\Request;
use Param\RBAC\Models\AuthSetting;
use Param\RBAC\Models\PasswordRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthSettingController extends Controller
{
    /**
     * Show authentication settings page
     */
    public function index()
    {
        $title = 'Auth-Settings';
        return view('master.AuthSettings.setting', compact('title'));
    }

    /**
     * Get all authentication settings
     */
    public function getAuthenticationSettings()
    {
        $settings = AuthSetting::where('IsActive', 1)->get();

        return response()->json(
            $settings->map(function ($s) {
                return [
                    'authSettingId' => $s->AuthSettingId,
                    'authCode'      => $s->AuthCode,
                    'isEnabled'     => (bool) $s->IsEnabled,
                    'otpattempt'    => $s->OTPAttempt,
                    'otpresetTime'  => $s->OTPResetTime,
                    'otpexpiryTime' => $s->OTPExpiryTime,
                ];
            })
        );
    }

    /**
     * Update authentication settings
     */
    public function updateAuthenticationSettings(Request $request)
    {
        $data = $request->validate([
            '*.AuthSettingId' => 'required|integer',
            '*.AuthCode' => 'required|string',
            '*.IsEnabled' => 'required|boolean',
            '*.Otpattempt' => 'nullable|integer',
            '*.OtpresetTime' => 'nullable|integer',
            '*.OtpexpiryTime' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                AuthSetting::updateOrCreate(
                    ['AuthSettingId' => $item['AuthSettingId']],
                    [
                        'AuthCode'       => $item['AuthCode'],
                        'IsEnabled'      => $item['IsEnabled'] ? 1 : 0,
                        'OTPAttempt'     => $item['Otpattempt'] ?? null,
                        'OTPResetTime'   => $item['OtpresetTime'] ?? null,
                        'OTPExpiryTime'  => $item['OtpexpiryTime'] ?? null,
                        'UpdatedOn'      => Carbon::now()
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'Authentication settings updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating auth settings', ['message' => $e->getMessage()]);
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all password rules
     */
    public function getPasswordRules()
    {
        return response()->json(
            PasswordRule::all()->map(function ($r) {
                return [
                    'ruleId'    => $r->RuleId,
                    'ruleCode'  => $r->RuleCode,
                    'ruleValue' => $r->RuleValue,
                    'isEnabled' => (bool) $r->IsEnabled
                ];
            })
        );
    }

    /**
     * Update password rules
     */
    public function updatePasswordRules(Request $request)
    {
        try {
            Log::info('Password Rules Update Request:', $request->all());

            $data = $request->validate([
                '*.RuleId' => 'required|integer',
                '*.RuleCode' => 'required|string',
                '*.IsEnabled' => 'required|boolean',
                '*.RuleValue' => 'required|integer',
            ]);

            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($data as $rule) {
                $ruleNameMap = [
                    'MIN_LENGTH' => 'Minimum Length',
                    'UPPERCASE' => 'Uppercase Letters',
                    'LOWERCASE' => 'Lowercase Letters',
                    'NUMBER' => 'Numbers',
                    'SPECIAL_CHAR' => 'Special Characters'
                ];

                $ruleName = $ruleNameMap[$rule['RuleCode']] ?? $rule['RuleCode'];

                $updated = PasswordRule::updateOrCreate(
                    ['RuleId' => $rule['RuleId']],
                    [
                        'RuleCode'  => $rule['RuleCode'],
                        'RuleName'  => $ruleName,
                        'RuleValue' => $rule['RuleValue'],
                        'IsEnabled' => $rule['IsEnabled'] ? 1 : 0,
                        'UpdatedOn' => Carbon::now()
                    ]
                );

                if ($updated) {
                    $updatedCount++;
                }
            }

            DB::commit();

            Log::info("Password rules updated successfully. Updated {$updatedCount} rules.");

            return response()->json([
                'result' => true,
                'message' => 'Password rules updated successfully',
                'updatedCount' => $updatedCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in updatePasswordRules:', $e->errors());
            return response()->json([
                'result' => false,
                'message' => 'Validation error: ' . implode(', ', array_flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating password rules:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'result' => false,
                'message' => 'Error updating password rules: ' . $e->getMessage()
            ], 500);
        }
    }
}
