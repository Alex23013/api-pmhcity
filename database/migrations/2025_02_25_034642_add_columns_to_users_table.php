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
            $table->string('lastname')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('profile_status')->default('new');
            $table->string('profile_picture')->nullable();
            $table->string('phone')->nullable(); // this is only filled when phone is verified
        

            // seller brand by default
            $table->unsignedBigInteger('role_id')->index()->default(2); 

            // city lille of metropole Lille by default
            $table->unsignedBigInteger('city_id')->index()->default(1); 

            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['lastname','verification_code', 'profile_status', 'profile_picture', 'role_id','phone','city_id']);
            });
        });
    }
};
