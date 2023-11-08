<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StagedApplicationCompanion extends Model
{
    use HasFactory;

    protected $table = 'stagedapplicationcompanion';

    protected $fillable = [
        'applicationID',
        'userID'
    ];
}
