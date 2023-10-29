<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pickupFormAnswer extends Model
{
    use HasFactory;

    protected $table = 'pickupformanswers';

    protected $fillable = [
        'applicationID',
        'questionID',
        'selected_optionID',
        'answer_text'
    ];
}
