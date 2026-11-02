<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aml_checks', function (Blueprint $table) {
            $table->boolean('is_limited')
                ->nullable()
                ->after('flow');
        });
    }

    public function down(): void
    {
        Schema::table('aml_checks', function (Blueprint $table) {
            $table->dropColumn('is_limited');
        });
    }
};
