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
        Schema::create('visit_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->date('followup_date');
            $table->enum('followup_type', ['call', 'chat', 'visit', 'email', 'other']);
            $table->text('notes')->nullable();
            $table->text('outcome')->nullable();
            $table->timestamps();

            $table->index('followup_date');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_followups');
    }
};
