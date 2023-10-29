<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnFormAnswer extends Model
{
    use HasFactory;

    protected $table = 'returnformanswers';

    protected $fillable = [
        'applicationID',
        'questionID',
        'selected_optionID',
        'answer_text'
    ];
}
