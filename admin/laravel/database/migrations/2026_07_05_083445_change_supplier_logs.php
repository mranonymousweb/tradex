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
        Schema::table('supplier_logs', function (Blueprint $table) {
            $table->decimal('amount', 32, 12)->change();
            $table->decimal('total', 32, 12)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_logs', function (Blueprint $table) {
            // Replace these with the previous precision/scale
            $table->decimal('amount', 24, 8)->change();
            $table->decimal('total', 24, 8)->change();
        });
    }
};
