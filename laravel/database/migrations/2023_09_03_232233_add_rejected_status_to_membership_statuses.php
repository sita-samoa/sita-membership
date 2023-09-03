<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('membership_statuses')->insert(
            array(
                'id' => 8,
                'title' => 'Rejected',
                'code' => 'rejected'
            )
            );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('membership_statuses')->delete(8);
    }
};
