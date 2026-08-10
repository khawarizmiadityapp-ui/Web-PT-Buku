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
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->date('date');
            $table->string('customer_name');
            $table->integer('total_items')->default(0);
            $table->string('recipient_name')->nullable();
            $table->enum('status', ['Completed', 'In-Progress', 'Canceled'])->default('In-Progress');
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
        Schema::dropIfExists('stock_outs');
    }
};
