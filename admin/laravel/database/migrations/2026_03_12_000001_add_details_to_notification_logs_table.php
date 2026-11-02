<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('notification_logs')->truncate();
        Schema::enableForeignKeyConstraints();

        Schema::table('notification_logs', function (Blueprint $table) {
            $table->string('supplier')->nullable()->after('channel');
            $table->string('recipient')->nullable()->after('supplier');
            $table->string('pattern')->nullable()->after('recipient');
            $table->text('response')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->dropColumn(['supplier', 'recipient', 'pattern', 'response']);
        });
    }
};
