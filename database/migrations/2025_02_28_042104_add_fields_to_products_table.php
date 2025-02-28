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
            $table->foreignId('status_product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->json('size_ids')->nullable();
            $table->json('color_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status_product_id');
            $table->dropColumn('material_id');
            $table->dropColumn('brand_id');
            $table->dropColumn('size_ids');
            $table->dropColumn('color_ids');
        });
    }
};
