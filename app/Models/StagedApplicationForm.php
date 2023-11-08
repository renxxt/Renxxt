<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StagedApplicationForm extends Model
{
    use HasFactory;

    protected $table = 'stagedapplicationforms';

    protected $fillable = [
        'userID',
        'deviceID',
        'companion',
        'application_time',
        'estimated_pickup_time',
        'estimated_return_time',
        'target'
    ];

    public function companions()
    {
        return $this->hasMany(StagedApplicationCompanion::class, 'applicationID', 'applicationID')
                ->leftJoin('users AS U', 'U.userID', '=', 'stagedapplicationcompanion.userID');
    }
}
