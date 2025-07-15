<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_yields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained();
            $table->string('application_type'); // "muro", "piso", "cimiento", etc.
            $table->decimal('yield_per_unit', 10, 4); // Rendimiento por unidad
            $table->string('unit_measure'); // "m²", "m³", "piezas", etc.
            $table->text('notes'); // Notas sobre aplicación
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_yields');
    }
}; 