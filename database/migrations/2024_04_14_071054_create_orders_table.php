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
            $table->integer('order_status');
            $table->boolean('payment_status');
            $table->boolean('active');
            $table->integer('price');
            $table->foreignId('user_id')->constrained('users')->CascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->CascadeOnDelete();
            $table->timestamps();
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
