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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();

            // Basic
            $table->string('quotation_no')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            // Job context
            $table->string('job_type'); // air, ocean, trucking (atau enum kalau mau)
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('incoterms')->nullable();

            // Validity
            $table->date('valid_until')->nullable();

            // Status lifecycle
            $table->enum('status', [
                'draft',
                'sent',
                'accepted',
                'rejected',
                'expired'
            ])->default('draft');

            // Notes
            $table->text('notes')->nullable();

            // Snapshot (optional tapi bagus)
            $table->string('customer_name')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
