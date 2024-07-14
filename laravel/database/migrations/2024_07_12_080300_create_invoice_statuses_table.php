<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('title');
            $table->timestamps();
        });

        // Insert default data
        DB::table('invoice_statuses')->insert([
            ['id' => '1', 'code' => 'due', 'title' => 'Due'],
            ['id' => '2', 'code' => 'paid', 'title' => 'Paid'],
            ['id' => '3', 'code' => 'cancelled', 'title' => 'Cancelled'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_statuses');
    }
};
