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
        Schema::create('member_work_experiences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id');
            $table->string('organisation');
            $table->string('position');
            $table->text('responsibilities');
            $table->date('from_date');
            $table->date('to_date');

            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_work_experiences');
    }
};
