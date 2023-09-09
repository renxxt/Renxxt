<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'attributeID',
        'questionID',
        'order',
        'required'
    ];
}
