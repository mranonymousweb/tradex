<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Token contract whitelist.
 *
 * Deposits used to be matched to a currency by the symbol the token reports about
 * itself, which anyone can choose. A token deployed as "USDT" and sent to a public
 * deposit address would have been credited as real USDT. The contract address is the
 * only thing that cannot be forged, so it is stored per currency/network pair and the
 * deposit scanner matches on it instead.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('network_currencies', function (Blueprint $table) {
            $table->string('contract_address')
                ->nullable()
                ->after('network_id')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('network_currencies', function (Blueprint $table) {
            $table->dropIndex(['contract_address']);
            $table->dropColumn('contract_address');
        });
    }
};
