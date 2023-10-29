<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelApplication extends Model
{
    use HasFactory;

    protected $table = 'cancelapplication';

    protected $fillable = [
        'applicationID',
        'result'
    ];
}
