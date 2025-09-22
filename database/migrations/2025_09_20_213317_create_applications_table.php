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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('store_name');
            $table->string('store_location');
            $table->string('store_url')->nullable();
            $table->text('store_description')->nullable();

            $table->text('admin_notes')->nullable();
            $table->string('category');
            $table->enum('status', ['new', 'contacted', 'in_progress', 'onboarded', 'rejected', 'completed'])->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
