<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wallet_repositories', function (Blueprint $table) {
            // Addresses created before the deposit callback existed have no
            // callback_url on the provider side and must be back-filled.
            $table->timestamp('callback_registered_at')->nullable()->after('supplier');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_repositories', function (Blueprint $table) {
            $table->dropColumn('callback_registered_at');
        });
    }
};
