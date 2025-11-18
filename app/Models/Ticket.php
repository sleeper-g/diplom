<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['session_id', 'seat_id', 'qr_code', 'price'];
    // Билет привязан к сеансу
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    // Билет привязан к месту
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
