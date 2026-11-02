<?php

use App\Models\UserCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('code');
            $table->string('section')->nullable();
            $table->timestamp('code_time')->nullable();
            $table->enum('type', [UserCode::TYPE_SMS, UserCode::TYPE_EMAIL, UserCode::TYPE_BOTH])->nullable();
            $table->enum('status', [UserCode::STATUS_USED, UserCode::STATUS_UNUSED])->default(UserCode::STATUS_UNUSED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_codes');
    }
};
