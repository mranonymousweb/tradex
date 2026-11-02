<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->nullOnDelete()->cascadeOnUpdate();
            $table->string('notify_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_notifications');
    }
};
