<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMaster extends Model
{
    protected $table = 'role_master';

    protected $fillable = [
        'role_name',
        'priority_order',
        'is_active',
        'created_by',
        'created_date',
        'update_date',
        'last_modify',
        'deleted_date'
    ];

    public $timestamps = false;
}
