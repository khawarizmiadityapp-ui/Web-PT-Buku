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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique(); // SKU-00441
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->integer('system_stock')->default(0);
            $table->integer('physical_stock')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->string('unit')->default('pcs'); // pcs, box, carton
            $table->string('image')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
