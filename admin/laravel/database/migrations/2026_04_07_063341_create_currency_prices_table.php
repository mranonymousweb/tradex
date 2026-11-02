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
        // if (!Schema::connection('mongodb')->hasCollection('currency_prices')) {
        //     Schema::connection('mongodb')->create('currency_prices', function (Blueprint $collection) {
        //         $collection->string('pair');
        //         $collection->double('price');
        //         $collection->date('time');
        //     });
        // }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('currency_prices');
    }
};
