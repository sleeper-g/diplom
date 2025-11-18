<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $fillable = ['name', 'rows', 'seats_per_row'];

    // Зал имеет много мест
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    // Зал может иметь много сеансов
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
