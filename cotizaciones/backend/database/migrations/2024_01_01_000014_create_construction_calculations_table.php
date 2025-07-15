<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('construction_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained();
            $table->string('construction_type'); // "recamara", "sala", etc.
            $table->decimal('length', 8, 2); // Largo en metros
            $table->decimal('width', 8, 2); // Ancho en metros
            $table->decimal('height', 8, 2); // Alto en metros
            $table->json('calculated_materials'); // Materiales calculados automáticamente
            $table->json('calculated_labor'); // Mano de obra calculada
            $table->decimal('materials_subtotal', 12, 2);
            $table->decimal('labor_subtotal', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('construction_calculations');
    }
}; 