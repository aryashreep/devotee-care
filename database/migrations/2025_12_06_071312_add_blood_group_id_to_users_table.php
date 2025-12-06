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
            if (!Schema::hasColumn('users', 'blood_group_id')) {
                $table->foreignId('blood_group_id')->nullable()->constrained('blood_groups');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'blood_group_id')) {
                $table->dropForeign(['blood_group_id']);
                $table->dropColumn('blood_group_id');
            }
        });
    }
};
