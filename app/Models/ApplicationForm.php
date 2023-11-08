<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $table = 'applicationforms';

    protected $fillable = [
        'userID',
        'deviceID',
        'companion',
        'estimated_pickup_time',
        'estimated_return_time',
        'pickup_time',
        'return_time',
        'target',
        'state'
    ];

    public function companions()
    {
        return $this->hasMany(ApplicationCompanion::class, 'applicationID', 'applicationID')
                ->leftJoin('users AS U', 'U.userID', '=', 'applicationformcompanion.userID');
    }

    public function cancel()
    {
        return $this->hasOne(CancelApplication::class, 'applicationID', 'applicationID');
    }

    public function approved()
    {
        return $this->hasOne(ApprovedApplication::class, 'applicationID', 'applicationID')
                ->leftJoin('users AS U', 'U.userID', '=', 'approvedapplication.approved_userID');
    }

    public function pickupformanswers()
    {
        return $this->hasMany(pickupFormAnswer::class, 'applicationID', 'applicationID')
                ->leftJoin('questions AS Q', 'Q.questionID', '=', 'pickupformanswers.questionID');
    }
}
