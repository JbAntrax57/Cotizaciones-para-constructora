<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/2024_01_01_000008_create_quote_services_table.php
return new class extends Migration {
    public function up() {
        Schema::create('quote_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }
};