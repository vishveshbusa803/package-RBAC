<?php

namespace Database\Seeders;

use App\Models\AuthSetting;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AuthenticationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $authSettings = [
            [
                'AuthCode' => 'CAPTCHA',
                'AuthName' => 'Captcha',
                'IsEnabled' => 1,
                'OTPAttempt' => null,
                'OTPResetTime' => null,
                'OTPExpiryTime' => null,
                'CreatedOn' => $now,
                'UpdatedOn' => $now,
                'IsActive' => 1,
            ],
            [
                'AuthCode' => 'EMAIL_VERIFY',
                'AuthName' => 'Email OTP',
                'IsEnabled' => 1,
                'OTPAttempt' => 5,
                'OTPResetTime' => 300,
                'OTPExpiryTime' => 600,
                'CreatedOn' => $now,
                'UpdatedOn' => $now,
                'IsActive' => 1,
            ],
            [
                'AuthCode' => 'MOBILE_VERIFY',
                'AuthName' => 'Mobile OTP',
                'IsEnabled' => 1,
                'OTPAttempt' => 5,
                'OTPResetTime' => 300,
                'OTPExpiryTime' => 600,
                'CreatedOn' => $now,
                'UpdatedOn' => $now,
                'IsActive' => 1,
            ],
            [
                'AuthCode' => 'TWO_FACTOR',
                'AuthName' => 'Two-Factor Authentication',
                'IsEnabled' => 1,
                'OTPAttempt' => 5,
                'OTPResetTime' => null,
                'OTPExpiryTime' => null,
                'CreatedOn' => $now,
                'UpdatedOn' => $now,
                'IsActive' => 1,
            ],
        ];

        foreach ($authSettings as $setting) {
            AuthSetting::updateOrCreate(
                ['AuthCode' => $setting['AuthCode']],
                $setting
            );
        }
    }
}
