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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->enum('status', ['new', 'contacted', 'interested', 'negotiating', 'closed_won', 'closed_lost'])->default('new');
            $table->string('source')->nullable(); // e.g. 'referral', 'facebook', 'walk-in'
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);

            $table->datetime('created_datetime')->nullable();
            $table->datetime('updated_datetime')->nullable();

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by_uid')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_uid')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
