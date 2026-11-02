<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('currency_deposit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('aml_check_id')->nullable()->after('validated_at')->index();
        });
    }

    public function down(): void
    {
        Schema::table('currency_deposit_logs', function (Blueprint $table) {
            $table->dropIndex(['aml_check_id']);
            $table->dropColumn('aml_check_id');
        });
    }
};
