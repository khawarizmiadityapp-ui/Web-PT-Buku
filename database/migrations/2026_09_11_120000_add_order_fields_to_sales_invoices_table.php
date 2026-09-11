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
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->string('order_code')->nullable()->unique()->after('invoice_number');
            $table->string('order_status')->default('pending')->after('payment_method'); // pending, confirmed, processing, ready, completed, cancelled
            $table->text('shipping_address')->nullable()->after('notes');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('customer_email')->nullable()->after('customer_phone');
            $table->json('status_history')->nullable()->after('order_status');
            $table->timestamp('status_updated_at')->nullable()->after('status_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'order_code',
                'order_status',
                'shipping_address',
                'customer_phone',
                'customer_email',
                'status_history',
                'status_updated_at',
            ]);
        });
    }
};
