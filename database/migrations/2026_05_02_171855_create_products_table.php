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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
 $table->foreignId('category_id')->constrained()->cascadeOnDelete();
      $table->string('name_ar');                    // شاي, برجر لحمة
      $table->string('name_en');                    // شاي, برجر لحمة
      $table->text('description_ar')->nullable();
      $table->text('description_en')->nullable();
      $table->decimal('price', 10, 2);           // 25.50
      $table->string('image')->nullable();
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
