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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name');
            $table->string('trade_name');
            $table->string('manufacture_company');
            $table->integer('available_quantity');
            $table->date('Ed');
            $table->integer('price');
            $table->string('photo')->nullable();
            $table->integer('sales')->nullable();
            $table->foreignId('admin_id')->constrained('admins')->CascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->CascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
