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
        Schema::create('back_orders', function (Blueprint $table) {
            $table->id();
            $table->integer("backorder_quantity");
            $table->string("status");
            $table->timestamps();


            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('purchase_id')->constrained('purchases');
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_orders');
    }
};
