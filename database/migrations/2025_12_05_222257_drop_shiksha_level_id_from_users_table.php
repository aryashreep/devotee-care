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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'shiksha_level_id')) {
                $table->dropForeign(['shiksha_level_id']);
                $table->dropColumn('shiksha_level_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'shiksha_level_id')) {
                $table->foreignId('shiksha_level_id')->nullable()->constrained('shiksha_levels');
            }
        });
    }
};
