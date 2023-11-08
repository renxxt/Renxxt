<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'userID';
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'name',
        'departmentID',
        'email',
        'phonenumber',
        'password',
        'positionID',
        'superiorID',
        'state'
    ];

    protected $hidden = [
        'password'
    ];
}
