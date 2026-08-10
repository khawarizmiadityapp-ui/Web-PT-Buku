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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_id')->unique(); // RET-2026-001
            $table->date('date');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('entity'); // Customer name or Supplier name
            $table->enum('type', ['SALES', 'PURCHASE']); // Return from customer or return to supplier
            $table->string('reason');
            $table->integer('items'); // Quantity returned
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};
