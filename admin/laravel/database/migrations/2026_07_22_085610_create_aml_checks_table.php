<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aml_checks', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('amlbot');
            $table->string('request_type');
            $table->string('account_mode')->nullable();
            $table->string('flow')->nullable();

            $table->string('uid')->nullable()->index();
            $table->string('status')->default('queued')->index();

            $table->string('address')->nullable()->index();
            $table->string('tx_hash')->nullable()->index();
            $table->string('asset')->nullable()->index();
            $table->string('network')->nullable();

            $table->decimal('risk_score', 12, 6)->nullable();
            $table->string('risk_score_level')->nullable();

            $table->json('signals_map')->nullable();
            $table->json('signal_tags')->nullable();
            $table->json('counterparty')->nullable();
            $table->json('connections')->nullable();
            $table->json('extras')->nullable();

            $table->boolean('has_blacklist_flag')->nullable();
            $table->json('blacklists_connections')->nullable();

            $table->decimal('amount_raw', 36, 0)->nullable();
            $table->decimal('amount_normalized', 36, 18)->nullable();

            $table->string('pdf_report')->nullable();
            $table->string('xls_report')->nullable();
            $table->text('investigation_link')->nullable();

            $table->boolean('monitoring_subscribed')->default(false);
            $table->timestamp('monitoring_subscribed_at')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aml_checks');
    }
};
