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
        Schema::create('whitelisting_ips', function (Blueprint $table) {
                $table->id();
                $table->ipAddress('ip')->unique(); // supports IPv4 + IPv6
                $table->string('label')->nullable(); // optional description (e.g. "Office", "VPN")
                $table->timestamps();
                $table->index('ip'); // speeds up lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whitelisting_ips');
    }
};
