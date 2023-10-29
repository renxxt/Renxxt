<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAttribute extends Model
{
    use HasFactory;

    protected $table = 'deviceattributes';

    protected $fillable = [
        'name',
        'display',
        'approved_layers',
        'approved_level',
        'pickup_form',
        'return_form',
        'companion_number'
    ];

    public function pickupForms()
    {
        return $this->hasMany(PickupForm::class, 'attributeID', 'attributeID')
                ->leftJoin('questions AS Q', 'Q.questionID', '=', 'pickupform.questionID')
                ->orderBy('order', 'asc');
    }

    public function returnForms()
    {
        return $this->hasMany(ReturnForm::class, 'attributeID', 'attributeID')
                ->leftJoin('questions AS Q', 'Q.questionID', '=', 'returnform.questionID')
                ->orderBy('order', 'asc');
    }
}
