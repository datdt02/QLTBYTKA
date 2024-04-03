<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department_User extends Model
{
    use HasFactory;
    protected $table = 'department_user';
    protected $fillable = [
        'user_id', 'department_id'
    ];
}
