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
        Schema::create('payments', function (Blueprint $table) {
          $table->id();
      $table->foreignId('order_id')->constrained()->cascadeOnDelete();
      $table->enum('method', ['cash', 'card', 'wallet']);
      $table->decimal('amount', 10, 2);          // المطلوب
      $table->decimal('received', 10, 2)->nullable(); // اللي اتدفع
      $table->decimal('change', 10, 2)->nullable();   // الباقي
      $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
