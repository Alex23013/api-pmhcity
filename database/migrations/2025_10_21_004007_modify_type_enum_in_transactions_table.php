<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        // Drop the old CHECK constraint
        DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_type_check;");

        // Add the new CHECK constraint with the updated values
        DB::statement("
            ALTER TABLE transactions
            ADD CONSTRAINT transactions_type_check
            CHECK (type IN (
                'earning',
                'withdrawal',
                'refund',
                'adjustment',
                'pmh_relay_commission',
                'pmh_city_commission'
            ));
        ");
    }

    public function down(): void
    {
        // Drop the updated CHECK constraint
        DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_type_check;");

        // Restore the original CHECK constraint
        DB::statement("
            ALTER TABLE transactions
            ADD CONSTRAINT transactions_type_check
            CHECK (type IN (
                'earning',
                'withdrawal',
                'refund',
                'adjustment'
            ));
        ");
    }
};
