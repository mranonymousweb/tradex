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
        Schema::create('asset_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->nullable()->constrained('user_assets', 'id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins', 'id')->nullOnUpdate()->nullOnDelete();
            $table->decimal('old_balance', 50, 12)->default(0);
            $table->decimal('new_balance', 50, 12)->default(0);
            $table->string('symbol')->nullable();
            $table->string('key')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_logs');
    }
};
