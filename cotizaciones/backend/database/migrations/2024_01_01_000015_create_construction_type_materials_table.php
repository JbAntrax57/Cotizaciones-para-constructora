<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('construction_type_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->string('application_type'); // "muro", "piso", "cimiento", etc.
            $table->decimal('quantity_per_unit', 10, 4); // Cantidad por unidad de área/volumen
            $table->string('unit_measure'); // "m²", "m³", "piezas", etc.
            $table->string('calculation_formula'); // Fórmula de cálculo (ej: "area * 20")
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('construction_type_materials');
    }
}; 