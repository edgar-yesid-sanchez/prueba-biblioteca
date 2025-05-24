<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Libro extends Model
{

    use HasFactory;
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