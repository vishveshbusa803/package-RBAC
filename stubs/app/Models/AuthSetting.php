<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthSetting extends Model
{
    use HasFactory;

    protected $table = 'authentication_settings';
    protected $primaryKey = 'AuthSettingId';
    protected $fillable = [
        'AuthCode',
        'AuthName',
        'IsEnabled',
        'OTPAttempt',
        'OTPResetTime',
        'OTPExpiryTime',
        'CreatedOn',
        'UpdatedOn',
        'DeletionDate',
        'IsActive',
        'UserId',
    ];
    public $timestamps = false;
    protected $dates = ['CreatedOn', 'UpdatedOn', 'DeletionDate'];

    /**
     * Helper method to get setting by AuthCode
     */
    public static function getSetting($authCode)
    {
        return self::where('AuthCode', $authCode)
            ->where('IsEnabled', 1)
            ->where('IsActive', 1)
            ->first();
    }
}
