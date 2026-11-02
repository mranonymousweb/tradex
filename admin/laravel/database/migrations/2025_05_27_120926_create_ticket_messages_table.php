<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->longText('message');
            $table->string('status')->default(\App\Models\TicketMessage::STATUS_NOT_SEEN);
            $table->timestamp('seen_at')->nullable();
            $table->string('from')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
