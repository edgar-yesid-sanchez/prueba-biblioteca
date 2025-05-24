<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();

            // Relación con usuarios
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relación con libros
            $table->foreignId('libro_id')->constrained()->onDelete('cascade');

            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion')->nullable();
            $table->string('estado')->default('pendiente');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
