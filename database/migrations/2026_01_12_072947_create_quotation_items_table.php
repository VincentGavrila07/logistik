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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();

            // Charge info
            $table->string('description'); // Ocean Freight, THC, dll
            $table->string('charge_type')->nullable(); // freight, local, dll

            // Vendor (optional, buat costing)
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();

            // Quantity
            $table->decimal('qty', 12, 2)->default(1);
            $table->string('unit')->nullable(); // container, kg, shipment

            // Currency
            $table->string('currency', 10);

            // Pricing
            $table->decimal('sell_rate', 18, 2)->default(0);
            $table->decimal('buy_rate', 18, 2)->default(0);

            // Calculated totals
            $table->decimal('amount_sell', 18, 2)->default(0);
            $table->decimal('amount_buy', 18, 2)->default(0);

            // Optional margin tracking
            $table->decimal('margin', 8, 2)->nullable(); // persen kalau mau

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
