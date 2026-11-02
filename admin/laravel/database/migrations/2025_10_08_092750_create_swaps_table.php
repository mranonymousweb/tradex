<?php

use App\Models\Swap;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('swaps', function (Blueprint $table) {
            $table->id()->startingValue(1745);

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('from_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->foreignId('to_currency_id')->constrained('currencies')->cascadeOnDelete();


            $table->string('status')->default(Swap::STATUS_WAITING);
            $table->string('supplier')->nullable();


            $table->string('from_currency_symbol');
            $table->string('to_currency_symbol');
            $table->decimal('from_amount', 30, 10)->default(0);
            $table->decimal('to_amount', 30, 10)->default(0);
            $table->decimal('to_final', 30, 10)->default(0);
            $table->decimal('from_price', 30, 10)->nullable();
            $table->decimal('to_price', 30, 10)->nullable();
            $table->decimal('fee', 30, 10)->default(0);
            $table->decimal('from_total', 30, 10)->nullable();
            $table->decimal('to_total', 30, 10)->nullable();
            $table->decimal('real_from_price', 30, 10)->nullable();
            $table->decimal('real_to_price', 30, 10)->nullable();
            $table->decimal('fee_amount', 30, 10)->nullable();
            $table->decimal('to_amount_by_supplier', 30, 10)->nullable();

            $table->text('description')->nullable();
            $table->string('txt_id')->nullable();
            $table->timestamp('done_at_by_site')->nullable();
            $table->timestamp('done_at_by_supplier')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swaps');
    }
};
