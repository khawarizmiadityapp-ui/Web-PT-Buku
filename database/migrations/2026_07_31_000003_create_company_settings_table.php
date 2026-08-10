<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('app_name')->default('PT Nusantara ERP');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('stock_alerts')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
