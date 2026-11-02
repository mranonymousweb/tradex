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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id()->startingValue(1156);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null')->onUpdate('cascade');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->enum('status', [\App\Models\Ticket::STATUS_NEW, \App\Models\Ticket::STATUS_PENDING, \App\Models\Ticket::STATUS_USER_ANSWERING, \App\Models\Ticket::STATUS_ANSWERED, \App\Models\Ticket::STATUS_CLOSED])
                ->default(\App\Models\Ticket::STATUS_NEW);
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->timestamp('seen_at')->nullable();
            $table->boolean('sms')->default(false);
            $table->string('file')->nullable();
            $table->enum('from', [\App\Models\Ticket::FROM_ADMIN, \App\Models\Ticket::FROM_USER])->nullable();
            $table->tinyInteger('star')->default(0);
            $table->text('comment')->nullable();
            $table->tinyInteger('priority')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
