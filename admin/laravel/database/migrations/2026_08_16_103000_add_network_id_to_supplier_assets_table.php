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
        Schema::table('supplier_assets', function (Blueprint $table) {
            $table->unsignedBigInteger('network_id')->nullable()->after('supplier_id');
            $table->foreign('network_id')
                ->references('id')
                ->on('networks')
                ->nullOnDelete();
            $table->dropColumn('network');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_assets', function (Blueprint $table) {
            $table->dropForeign(['network_id']);
            $table->dropColumn('network_id');
            $table->string('network')->nullable();
        });
    }
};
