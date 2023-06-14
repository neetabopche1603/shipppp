<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    public function scopeOlderThanOneDay($query)
    {
        return $query->where('created_at', '<', Carbon::yesterday());
    }

    public function scopeOlderThanHours($query)
    {
        return $query->where('created_at', '<=', Carbon::now()->subHours(23))->where('accepted','=',0);
    }

    protected $hidden = [
        // 'receptionist_id',
    ];
}
