<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketing_logs', function (Blueprint $table) {
            $table->foreignId('marketing_code_id')->nullable()->constrained('marketing_codes')->nullOnDelete()->nullOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_logs', function (Blueprint $table) {
            $table->dropForeign('marketing_logs_marketing_code_id_foreign');
            $table->dropColumn('marketing_code_id');
        });
    }
};
