<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'cinema_sessions';
    
    protected $fillable = ['movie_id', 'hall_id', 'start_time', 'end_time'];
    // Сеанс связан с фильмом
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Сеанс проходит в зале
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    // Сеанс имеет много билетов
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
