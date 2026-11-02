<?php

use App\Models\AuthenticationLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authentication_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('status')->default(AuthenticationLog::STATUS_WAITING);
            $table->string('step')->default(AuthenticationLog::STEP_1);
            $table->text('description')->nullable();
            $table->json('extra_data')->nullable();
            $table->timestamp('status_changed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication_logs');
    }
};
