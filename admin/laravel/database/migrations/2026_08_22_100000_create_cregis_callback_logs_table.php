<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cregis_callback_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('deposit');
            // Cregis re-pushes failed callbacks from its dashboard, so the payload
            // hash is what keeps a replay from crediting the same deposit twice.
            $table->string('event_hash')->unique();
            $table->string('cid')->nullable()->index();
            $table->string('tx_id')->nullable()->index();
            $table->string('chain_id')->nullable();
            $table->string('token_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('address')->nullable()->index();
            $table->string('memo')->nullable();
            $table->string('amount')->nullable();
            $table->string('status')->nullable();
            $table->string('block_height')->nullable();
            $table->string('block_time')->nullable();
            $table->boolean('signature_valid')->default(false);
            $table->json('payload');
            $table->timestamp('processed_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cregis_callback_logs');
    }
};
