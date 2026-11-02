<?php

use App\Models\SupplierExchange;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_exchanges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('supplier')->nullable();
            $table->string('status')->default(SupplierExchange::STATUS_ACTIVE);
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->decimal('extra_fee', 15, 10)->default(0);
            $table->string('exchange_supplier')->nullable();
            $table->string('option_receiver')->nullable();
            $table->string('networking')->nullable();
            $table->string('currency_receiver')->nullable();
            $table->string('price_supplier')->nullable();
            $table->bigInteger('start_nounce')->nullable();
            $table->bigInteger('end_nounce')->nullable();
            $table->text('withdraw_api_key')->nullable();
            $table->text('withdraw_secret_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_exchanges');
    }
};
