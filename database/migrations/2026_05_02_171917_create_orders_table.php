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
      $table->foreignId('user_id')->constrained();
      $table->string('order_number')->unique();  // ORD-20260502-0001
      $table->enum('type', ['dine_in', 'takeaway', 'delivery']);
      $table->enum('status', ['pending', 'preparing', 'ready', 'completed',
  'cancelled'])->default('pending');
      $table->decimal('subtotal', 10, 2);
      $table->decimal('tax', 10, 2)->default(0);
      $table->decimal('discount', 10, 2)->default(0);
      $table->decimal('total', 10, 2);
      $table->text('notes')->nullable();
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
