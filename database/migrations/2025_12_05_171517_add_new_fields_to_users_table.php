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
            $table->string('photo')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('marriage_anniversary_date')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->foreignId('education_id')->nullable()->constrained('education');
            $table->foreignId('profession_id')->nullable()->constrained('professions');
            $table->boolean('initiated')->nullable();
            $table->integer('rounds')->nullable();
            $table->foreignId('shiksha_level_id')->nullable()->constrained('shiksha_levels');
            $table->boolean('second_initiation')->nullable();
            $table->boolean('life_membership')->nullable();
            $table->string('life_member_no')->nullable();
            $table->string('temple')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['education_id']);
            $table->dropForeign(['profession_id']);
            $table->dropForeign(['shiksha_level_id']);

            $table->dropColumn([
                'photo',
                'marital_status',
                'marriage_anniversary_date',
                'state',
                'pincode',
                'education_id',
                'profession_id',
                'initiated',
                'rounds',
                'shiksha_level_id',
                'second_initiation',
                'life_membership',
                'life_member_no',
                'temple',
            ]);
        });
    }
};
