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
        Schema::table('supplier_exchanges', function (Blueprint $table) {
            $table->decimal('extra_fee_max_amount',20,10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_exchanges', function (Blueprint $table) {
            $table->dropColumn('extra_fee_max_amount');
        });
    }
};
