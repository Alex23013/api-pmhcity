<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verification_code');
            $table->dateTime('fpt_expires_at')->nullable(); //fpt stands for forget password token
            $table->string('forget_password_token')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_code')->nullable();
            $table->dropColumn(['fpt_expires_at', 'forget_password_token']);
        });
    }
};
