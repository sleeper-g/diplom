<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'description', 'duration'];
    // Фильм имеет много сеансов
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
