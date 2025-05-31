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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // Assuming price is a decimal with 2 decimal places
            $table->enum('type', ['أكل', 'بارد', 'ساخن']);
            $table->boolean('is_available')->default(true); // Assuming you want to track availability
            $table->unsignedBigInteger('menucategory_id'); // Foreign key to menu_categories table
            $table->string('image')->nullable(); // Assuming you want to store an image path
            $table->foreign('menucategory_id')->references('id')->on('menuCategories')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // Soft delete column
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
