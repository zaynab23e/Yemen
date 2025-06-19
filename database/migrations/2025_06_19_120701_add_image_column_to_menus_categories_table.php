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
        Schema::table('menuCategories', function (Blueprint $table) {
            $table->string('image')->nullable(); // Assuming you want to store an image path            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menuCategories', function (Blueprint $table) {
            //
        });
    }
};
