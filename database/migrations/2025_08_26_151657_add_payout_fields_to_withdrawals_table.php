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
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('iban')->nullable()->after('status');
            $table->string('operation_code')->nullable()->after('iban');
            $table->enum('method', ['bank', 'paypal', 'stripe', 'manual', 'other'])->default('bank')->after('operation_code');
            $table->text('notes')->nullable()->after('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['iban', 'operation_code', 'method', 'notes']);
        });
    }
};
