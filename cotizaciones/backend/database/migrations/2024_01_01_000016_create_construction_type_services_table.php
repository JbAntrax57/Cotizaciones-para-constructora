<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('construction_type_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('work_type'); // "ladrillo_pegado", "piso_colado", etc.
            $table->decimal('rate_per_unit', 10, 2); // Tarifa por unidad
            $table->string('unit_measure'); // "m²", "m³", "pieza", etc.
            $table->string('calculation_formula'); // Fórmula de cálculo (ej: "area * 150")
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('construction_type_services');
    }
}; 