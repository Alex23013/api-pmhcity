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
        Schema::table('products', function (Blueprint $table) {
            $table->string('size_ids')->nullable()->change();

            $table->dropColumn(['color_ids']);
            $table->string('color_id')->nullable();
            
            $table->string('pmh_reference_code')->nullable();
            $table->string('article_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   //be aware that we are losing this info in the rollback
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['pmh_reference_code', 'article_code', 'size_ids', 'color_id']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->json('size_ids')->nullable();
            $table->json('color_ids')->nullable();
        });
    }
};
