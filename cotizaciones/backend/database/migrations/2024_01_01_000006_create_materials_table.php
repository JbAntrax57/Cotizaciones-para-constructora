<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/2024_01_01_000006_create_materials_table.php
return new class extends Migration {
    public function up() {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('supplier');
            $table->string('unit');
            $table->decimal('unit_cost', 10, 2);
            $table->timestamps();
        });
    }
};