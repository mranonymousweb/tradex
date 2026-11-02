<?php

use App\Models\Option;
use App\Models\PatternOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pattern_options', function (Blueprint $table) {
            $table->dropUnique('pattern_options_unique_key_type_local');
            $table->string('supplier', 64)->default('')->after('local');
            $table->unique(['key', 'type', 'local', 'supplier'], 'pattern_options_unique_key_type_local_supplier');
        });

        PatternOption::query()
            ->where('type', PatternOption::TYPE_SMS)
            ->where('supplier', '')
            ->update(['supplier' => Option::SMS_SUPPLIER_MELI_PAYAMAK]);
    }

    public function down(): void
    {
        Schema::table('pattern_options', function (Blueprint $table) {
            $table->dropUnique('pattern_options_unique_key_type_local_supplier');
            $table->dropColumn('supplier');
            $table->unique(['key', 'type', 'local'], 'pattern_options_unique_key_type_local');
        });
    }
};
