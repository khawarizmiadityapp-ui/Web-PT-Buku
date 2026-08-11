<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE incoming_goods MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Pending'");
        } catch (\Exception $e) {
            // Fallback for sqlite or other drivers
            Schema::table('incoming_goods', function (Blueprint $table) {
                $table->string('status', 50)->default('Pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE incoming_goods MODIFY COLUMN status ENUM('Pending', 'Verified', 'Revised') NOT NULL DEFAULT 'Pending'");
        } catch (\Exception $e) {
            //
        }
    }
};
