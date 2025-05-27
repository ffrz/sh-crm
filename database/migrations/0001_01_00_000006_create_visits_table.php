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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('visit_date');
            $table->time('visit_time')->nullable();
            $table->text('purpose');
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'done', 'cancelled'])->default('planned');
            $table->date('next_followup_date')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->index('visit_date');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
