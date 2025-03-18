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
        Schema::table('sizes', function (Blueprint $table) {
            $table->unsignedBigInteger('size_type_id')->nullable()->after('id');
            $table->foreign('size_type_id')->references('id')->on('size_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            Schema::table('sizes', function (Blueprint $table) {
                $table->dropForeign(['size_type_id']);
                $table->dropColumn('size_type_id');
            });
        });
    }
};
