<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['hall_id', 'row', 'number', 'type'];
    // Место принадлежит залу
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    // Место может иметь много билетов (по разным сеансам)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
