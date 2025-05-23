<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;

class Libro extends Model
{

    protected $fillable = [
        'titulo', 
        'autor',
        'genero',
        'disponible'
    ];
  
    public function prestamos() {
        return $this->hasMany(Prestamo::class);
    }
}