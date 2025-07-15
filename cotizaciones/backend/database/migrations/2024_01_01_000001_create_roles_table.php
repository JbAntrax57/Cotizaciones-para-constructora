<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2024_01_01_000001_create_roles_table.php
return new class extends Migration {
    public function up() {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('roles');
    }
};
