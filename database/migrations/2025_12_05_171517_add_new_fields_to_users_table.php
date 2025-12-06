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
            if (!Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable();
            }
            if (!Schema::hasColumn('users', 'marital_status')) {
                $table->string('marital_status')->nullable();
            }
            if (!Schema::hasColumn('users', 'marriage_anniversary_date')) {
                $table->date('marriage_anniversary_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('users', 'pincode')) {
                $table->string('pincode')->nullable();
            }
            if (!Schema::hasColumn('users', 'education_id')) {
                $table->foreignId('education_id')->nullable()->constrained('education');
            }
            if (!Schema::hasColumn('users', 'profession_id')) {
                $table->foreignId('profession_id')->nullable()->constrained('professions');
            }
            if (!Schema::hasColumn('users', 'initiated')) {
                $table->boolean('initiated')->nullable();
            }
            if (!Schema::hasColumn('users', 'rounds')) {
                $table->integer('rounds')->nullable();
            }
            if (!Schema::hasColumn('users', 'second_initiation')) {
                $table->boolean('second_initiation')->nullable();
            }
            if (!Schema::hasColumn('users', 'life_membership')) {
                $table->boolean('life_membership')->nullable();
            }
            if (!Schema::hasColumn('users', 'life_member_no')) {
                $table->string('life_member_no')->nullable();
            }
            if (!Schema::hasColumn('users', 'temple')) {
                $table->string('temple')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'education_id')) {
                $table->dropForeign(['education_id']);
            }
            if (Schema::hasColumn('users', 'profession_id')) {
                $table->dropForeign(['profession_id']);
            }

            $columnsToDrop = [
                'photo', 'marital_status', 'marriage_anniversary_date', 'state', 'pincode',
                'education_id', 'profession_id', 'initiated', 'rounds', 'second_initiation',
                'life_membership', 'life_member_no', 'temple'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
