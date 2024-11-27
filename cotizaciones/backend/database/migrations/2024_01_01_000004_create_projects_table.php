<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/2024_01_01_000004_create_projects_table.php
return new class extends Migration {
    public function up() {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->string('name');
            $table->text('description');
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['planning', 'in_progress', 'completed']);
            $table->timestamps();
        });
    }
};