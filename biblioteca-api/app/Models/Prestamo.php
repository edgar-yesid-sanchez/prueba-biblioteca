<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    
    protected $fillable = [
        'user_id', 'libro_id', 'fecha_prestamo', 'fecha_devolucion', 'estado'
    ];
    
    public function libro() {
        return $this->belongsTo(Libro::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
