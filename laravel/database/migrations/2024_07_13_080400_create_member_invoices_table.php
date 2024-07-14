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
        Schema::create('member_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_status_id')->constrained();  //due, paid, cancelled
            $table->date('invoice_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->float('amount')->default(0);
            $table->date('pay_before_date')->nullable();
            $table->date('paid_date')->nullable();
            // document fields
            $table->string('title')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('to_delete')->default(false);
            // timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_invoices');
    }
};
