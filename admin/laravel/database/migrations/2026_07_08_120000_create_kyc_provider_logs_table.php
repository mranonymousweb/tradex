<?php

use App\Models\KycProviderLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kyc_provider_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('provider')->default(KycProviderLog::PROVIDER_ID_ANALYZER);
            $table->string('source')->nullable();
            $table->string('reference')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('event')->nullable();
            $table->string('decision')->nullable();
            $table->string('status')->default(KycProviderLog::STATUS_PENDING);
            $table->json('raw_payload')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['provider', 'reference']);
            $table->index(['provider', 'user_id', 'source']);
            $table->index(['provider', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_provider_logs');
    }
};
