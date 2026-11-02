<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->string('validation_status')
                ->nullable()
                ->after('supplier')
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

            $table->unsignedBigInteger('aml_check_id')
                ->nullable()
                ->after('validated_at')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropIndex(['validation_status']);
            $table->dropIndex(['validation_type']);
            $table->dropIndex(['aml_check_id']);

            $table->dropColumn([
                'validation_status',
                'validation_type',
                'validation_note',
                'validation_payload',
                'validated_at',
                'aml_check_id',
            ]);
        });
    }
};
