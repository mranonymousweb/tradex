<?php

use App\Models\NotificationLogs;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable');
            $table->enum('status', [\App\Models\NotificationLogs::STATUS_SUCCESS, \App\Models\NotificationLogs::STATUS_WAITING, \App\Models\NotificationLogs::STATUS_FAIL])
                ->default(\App\Models\NotificationLogs::STATUS_WAITING);
            $table->string('channel')->nullable();
            $table->string('section')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
