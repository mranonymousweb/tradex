<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('networks', function (Blueprint $table) {
            $table->string('aml_asset')
                ->nullable()
                ->after('symbol')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('networks', function (Blueprint $table) {
            $table->dropIndex(['aml_asset']);
            $table->dropColumn('aml_asset');
        });
    }
};
