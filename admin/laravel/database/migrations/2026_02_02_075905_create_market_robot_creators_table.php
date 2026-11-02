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
        // Schema::connection('mongodb')->create('market_robot_creators', function (Blueprint $collection) {

        //     $collection->index('currency_id');
        //     $collection->string('symbol', 50)->nullable();

        //     $collection->decimal('leastAmount');
        //     $collection->decimal('mostAmount');

        //     // percentages
        //     $collection->decimal('sellPercent');
        //     $collection->decimal('buyPercent');
        //     $collection->decimal('donePercent');

        //     // status & timing
        //     $collection->integer('status')->default(1);
        //     $collection->integer('type')->default(1);
        //     $collection->integer('secount');

        //     // embedded currencies
        //     $collection->array('currencies');
        //     // example: [{ id: 1, symbol: "BTC" }]

        //     // timestamps
        //     $collection->timestamps();

        //     // indexes
        //     $collection->index('status');
        //     $collection->index('secount');



        // });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('market_robot_creators');
    }
};
