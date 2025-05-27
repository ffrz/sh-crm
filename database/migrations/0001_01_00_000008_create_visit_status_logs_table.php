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
        Schema::create('visit_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->enum('status', ['planned', 'done', 'cancelled']);
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('changed_at')->useCurrent();

            $table->index('visit_id');
            $table->index('changed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_status_logs');
    }
};
