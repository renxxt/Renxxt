<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display',
        'approved_layers',
        'approved_level',
        'pickup_form',
        'return_form',
        'companion_number'
    ];
}
