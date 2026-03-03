<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordRule extends Model
{
    protected $table = 'password_rules';
    protected $primaryKey = 'RuleId';
    public $timestamps = false;

    protected $fillable = [
        'RuleCode',
        'RuleValue',
        'IsEnabled',
        'CreatedOn',
        'UpdatedOn'
    ];
}
