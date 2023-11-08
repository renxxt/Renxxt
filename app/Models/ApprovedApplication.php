<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedApplication extends Model
{
    use HasFactory;

    protected $table = 'approvedapplication';

    protected $fillable = [
        'applicationID',
        'approved_userID'
    ];
}
