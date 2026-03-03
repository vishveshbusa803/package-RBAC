<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTwoFactor extends Model
{
    use HasFactory;

    protected $table = 'user_two_factor';

    protected $fillable = [
        'emp_id',
        'user_id',
        'secret_key',
        'is_enabled',
        'is_active',
        'is_setup_completed'
    ];

    public $timestamps = false;

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
