<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->decimal('old_balance', 32, 12)->nullable()->after('final_amount');
            $table->decimal('new_balance', 32, 12)->nullable()->after('old_balance');
            $table->string('asset_key')->nullable()->after('new_balance');
        });
    }

    public function down(): void
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn(['old_balance', 'new_balance', 'asset_key']);
        });
    }
};
