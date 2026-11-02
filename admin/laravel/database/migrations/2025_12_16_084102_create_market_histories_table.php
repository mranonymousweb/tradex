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
        Schema::create('market_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('market_id')->unsigned()->nullable()->index();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('sub_market_id')->unsigned()->index()->nullable();
            $table->foreign('sub_market_id')->references('id')->on('markets')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->decimal('amount', 50, 30)->index()->comment('final amount that was traded by both side');
            $table->decimal('price', 50, 30)->index()->comment('final price that was traded by both side');
            $table->decimal('global_price', 50, 30)->index()->comment('global price that was traded by both side');
            $table->boolean('is_robot')->default(false);
            $table->jsonb('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_histories');
    }
};
