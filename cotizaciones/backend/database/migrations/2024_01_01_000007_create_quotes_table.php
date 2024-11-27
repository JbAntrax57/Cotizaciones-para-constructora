<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/2024_01_01_000007_create_quotes_table.php
return new class extends Migration {
    public function up() {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected']);
            $table->timestamps();
        });
    }
};