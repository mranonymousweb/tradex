<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aml_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('amlbot_monitoring');
            $table->string('event_hash')->unique();
            $table->string('uid')->nullable()->index();
            $table->string('address')->nullable()->index();
            $table->string('asset')->nullable()->index();
            $table->string('tonce');
            $table->boolean('signature_valid')->default(false);
            $table->json('payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aml_webhook_events');
    }
};
