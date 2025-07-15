<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('labor_rates', function (Blueprint $table) {
            $table->id();
            $table->string('work_type'); // "ladrillo_pegado", "piso_colado", "cimiento", etc.
            $table->string('description');
            $table->decimal('rate_per_unit', 10, 2); // Tarifa por unidad
            $table->string('unit_measure'); // "pieza", "m²", "m³", etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('labor_rates');
    }
}; 