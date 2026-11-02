<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_assets', function (Blueprint $table) {
            // A single wallet holds the same symbol on several networks,
            // so the symbol alone cannot identify an asset row.
            $table->dropUnique(['supplier_id', 'wallet_id', 'symbol']);
            $table->index(['supplier_id', 'wallet_id']);
        });
    }

    public function down(): void
    {
        Schema::table('supplier_assets', function (Blueprint $table) {
            $table->dropIndex(['supplier_id', 'wallet_id']);
            $table->unique(['supplier_id', 'wallet_id', 'symbol']);
        });
    }
};
