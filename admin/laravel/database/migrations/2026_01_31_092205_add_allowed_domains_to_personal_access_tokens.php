<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        DB::statement("
            ALTER TABLE personal_access_tokens
            ALTER COLUMN abilities
            TYPE jsonb
            USING abilities::jsonb
        ");

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->jsonb('allowed_domains')
                ->nullable()
                ->after('expires_at');
        });
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE personal_access_tokens
            ALTER COLUMN abilities
            TYPE text
            USING abilities::text
        ");

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('allowed_domains');
        });
    }
};


/**
|--------------------------------------------------------------------------
| Personal Access Tokens – Database Enhancements Document
|--------------------------------------------------------------------------
|
| This migration extends Laravel Sanctum's `personal_access_tokens` table
| to support advanced API security features such as:
|
| 1. Ability-based authorization using JSON storage
| 2. Domain-level access restriction per token
|
|--------------------------------------------------------------------------
| WHY THESE CHANGES WERE NECESSARY
|--------------------------------------------------------------------------
|
| 1. abilities (text → jsonb)
| -----------------------------
| Laravel Sanctum internally works with abilities as an array.
| In PostgreSQL, storing this data as `jsonb` provides:
|
| - Native JSON querying
| - Better performance
| - Type safety
| - Compatibility with Laravel `$casts = ['abilities' => 'array']`
|
| PostgreSQL cannot automatically cast `text` to `jsonb`,
| therefore we explicitly use:
|
|     USING abilities::jsonb
|
| to safely migrate existing data.
|
|--------------------------------------------------------------------------
| 2. allowed_domains (jsonb)
| --------------------------
| This column stores a list of domains explicitly allowed to use
| a specific API token.
|
| Structure example:
|
| [
|   {
|     "id": 1,
|     "domain": "example.com",
|     "created_at": "2026-02-01 10:30:00"
|   }
| ]
|
| Reasons for using JSONB instead of a separate table:
|
| - Domains are tightly coupled to a single token
| - No need for joins
| - Easy atomic updates
| - Clean rollback
|
|--------------------------------------------------------------------------
| DATA SAFETY
|--------------------------------------------------------------------------
|
| - Existing user tokens remain valid
| - No breaking changes for previously issued tokens
| - User authentication flow is not affected
|
|--------------------------------------------------------------------------
| ROLLBACK STRATEGY
|--------------------------------------------------------------------------
|
| - abilities are safely converted back to text
| - allowed_domains column is removed
|
 */

