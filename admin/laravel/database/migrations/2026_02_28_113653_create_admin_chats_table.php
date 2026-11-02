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
        Schema::create('admin_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_admin_id')->constrained('admins', 'id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('to_admin_id')->constrained('admins', 'id')->cascadeOnUpdate()->nullOnDelete();
            $table->longText('message')->nullable();
            $table->dateTime('seen_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_chats');
    }
};
