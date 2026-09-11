<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure fcspackages.token_price exists. It is referenced in the FCpackage
     * model's $fillable and used by the admin FC package form, but it was
     * never actually created by a previous migration.
     */
    public function up(): void
    {
        if (Schema::hasTable('fcspackages') && !Schema::hasColumn('fcspackages', 'token_price')) {
            Schema::table('fcspackages', function (Blueprint $table) {
                $table->unsignedDecimal('token_price', 14, 4)->nullable()->after('default_token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fcspackages') && Schema::hasColumn('fcspackages', 'token_price')) {
            Schema::table('fcspackages', function (Blueprint $table) {
                $table->dropColumn('token_price');
            });
        }
    }
};
