<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCompanion extends Model
{
    use HasFactory;

    protected $table = 'applicationformcompanion';

    protected $fillable = [
        'applicationID',
        'userID'
    ];
}
