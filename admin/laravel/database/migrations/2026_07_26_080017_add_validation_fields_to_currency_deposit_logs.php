<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('currency_deposit_logs', function (Blueprint $table) {
            $table->string('validation_status')
                ->default('pending')
                ->after('wallet_status')
                ->index();

            $table->string('validation_type')
                ->nullable()
                ->after('validation_status')
                ->index();

            $table->text('validation_note')
                ->nullable()
                ->after('validation_type');

            $table->json('validation_payload')
                ->nullable()
                ->after('validation_note');

            $table->timestamp('validated_at')
                ->nullable()
                ->after('validation_payload');
        });

        DB::statement('ALTER TABLE currency_deposit_logs ALTER COLUMN is_used DROP DEFAULT');
        DB::statement('ALTER TABLE currency_deposit_logs ALTER COLUMN is_used TYPE boolean USING (is_used <> 0)');
        DB::statement('ALTER TABLE currency_deposit_logs ALTER COLUMN is_used SET DEFAULT false');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE currency_deposit_logs ALTER COLUMN is_used DROP DEFAULT');
        DB::statement('ALTER TABLE currency_deposit_logs ALTER COLUMN is_used TYPE double precision USING (CASE WHEN is_used THEN 1 ELSE 0 END)');
        DB::statement('ALTER TABLE currency_deposit_logs ALTER COLUMN is_used SET DEFAULT 0');

        Schema::table('currency_deposit_logs', function (Blueprint $table) {
            $table->dropIndex(['validation_status']);
            $table->dropIndex(['validation_type']);

            $table->dropColumn([
                'validation_status',
                'validation_type',
                'validation_note',
                'validation_payload',
                'validated_at',
            ]);
        });
    }
};
