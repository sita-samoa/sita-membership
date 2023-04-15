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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('title');
            $table->date('dob');
            // gender - constraint
            // member type - constraint
            $table->string('job_title');
            $table->string('current_employer');
            $table->string('home_address');
            $table->string('home_phone');
            $table->string('home_mobile');
            $table->string('home_email');
            $table->string('work_address');
            $table->string('work_phone');
            $table->string('work_mobile');
            $table->string('work_email');
            $table->string('other_membership');
            // membership status - constraint
            $table->mediumText('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
