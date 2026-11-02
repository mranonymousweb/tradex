<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_assets', function (Blueprint $table) {
            $table->string('wallet_id', 255)->nullable()->after('supplier_id');
            $table->dropUnique(['address']);
            $table->string('address', 255)->nullable()->change();
            $table->unique(['supplier_id', 'wallet_id', 'symbol']);
        });
    }

    public function down(): void
    {
        Schema::table('supplier_assets', function (Blueprint $table) {
            $table->dropUnique(['supplier_id', 'wallet_id', 'symbol']);
            $table->dropColumn('wallet_id');
            $table->string('address', 255)->nullable(false)->change();
            $table->unique('address');
        });
    }
};
