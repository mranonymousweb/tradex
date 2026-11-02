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
        //
        Schema::table('wallet_repositories', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
        });
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('wallet_repositories', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
        });
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
        });
    }
};
