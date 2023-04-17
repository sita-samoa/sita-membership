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
            // @todo - ensure member remains even if user is deleted
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->foreignId('title_id')->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('gender_id')->nullable();
            $table->foreignId('membership_type_id')->constrained();
            $table->string('job_title')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('home_address')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('home_mobile')->nullable();
            $table->string('home_email')->nullable();
            $table->string('work_address')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_mobile')->nullable();
            $table->string('work_email')->nullable();
            $table->string('other_membership')->nullable();
            $table->foreignId('membership_status_id')->default(1);
            $table->mediumText('note')->nullable();
            $table->foreignId('membership_application_status_id')->nullable();
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
