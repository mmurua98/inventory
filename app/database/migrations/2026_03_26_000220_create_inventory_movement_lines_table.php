<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_movement_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_movement_id')->constrained('inventory_movements');
            $table->unsignedInteger('line_no');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('qty', 12, 3);
            $table->decimal('unit_cost', 14, 4)->nullable();
            $table->timestamps();

            $table->unique(['inventory_movement_id', 'line_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movement_lines');
    }
};
