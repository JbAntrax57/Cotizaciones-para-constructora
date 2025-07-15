<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('construction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Recámara", "Sala", "Cocina", etc.
            $table->text('description');
            $table->json('default_specs'); // Altura estándar, acabados, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('construction_types');
    }
}; 