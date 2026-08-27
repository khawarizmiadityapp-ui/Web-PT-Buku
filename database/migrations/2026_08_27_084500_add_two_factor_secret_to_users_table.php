<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Services\TotpService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->string('two_factor_secret', 64)->nullable()->after('password');
            }
        });

        // Initialize secret for all users
        $users = User::all();
        foreach ($users as $user) {
            if (empty($user->two_factor_secret)) {
                $user->two_factor_secret = TotpService::generateSecret(16);
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'two_factor_secret')) {
                $table->dropColumn('two_factor_secret');
            }
        });
    }
};
