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
        Schema::table('product_returns', function (Blueprint $table) {
            $table->foreignId('sales_invoice_id')->nullable()->after('product_id')->constrained()->onDelete('set null');
            $table->decimal('unit_price', 15, 2)->default(0)->after('items');
            $table->decimal('total_amount', 15, 2)->default(0)->after('unit_price');
            $table->string('refund_method')->nullable()->after('total_amount');
            $table->decimal('refund_amount', 15, 2)->default(0)->after('refund_method');
            $table->decimal('restocking_fee', 15, 2)->default(0)->after('refund_amount');
            $table->string('proof_image')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_returns', function (Blueprint $table) {
            $table->dropForeign(['sales_invoice_id']);
            $table->dropColumn([
                'sales_invoice_id',
                'unit_price',
                'total_amount',
                'refund_method',
                'refund_amount',
                'restocking_fee',
                'proof_image'
            ]);
        });
    }
};
