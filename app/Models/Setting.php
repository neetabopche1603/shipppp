<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax',
    ];

}
