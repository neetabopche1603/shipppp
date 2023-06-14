<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $casts = [
        'item_type' => 'array'
    ];

    protected $hidden = [
        'departure_address',
        'arrival_address',
    ];

    // public function getItemType()
    // {
    //     return json_decode($this->attributes['item_type']);
    // }

}
