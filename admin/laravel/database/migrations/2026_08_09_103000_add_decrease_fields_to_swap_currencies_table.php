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
        Schema::table('swap_currencies', function (Blueprint $table) {
            $table->float('decrease_from_currency_percentage')->nullable()->after('min_amount');
            $table->float('max_decrease_from_currency_amount')->nullable()->after('decrease_from_currency_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('swap_currencies', function (Blueprint $table) {
            $table->dropColumn([
                'decrease_from_currency_percentage',
                'max_decrease_from_currency_amount',
            ]);
        });
    }
};
