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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2); // Total order price
            $table->decimal('discount', 10, 2)->default(0); // Discount applied
            $table->decimal('final_price', 10, 2); // Total after discount & shipping
            $table->enum('payment_method',['cash','visa'])->nullable(); // e.g., 'credit_card', 'paypal'
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key to users table
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
