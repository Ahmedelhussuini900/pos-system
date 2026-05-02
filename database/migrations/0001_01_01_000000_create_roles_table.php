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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->unique();         // admin, cashier, kitchen
            $table->string('name_en')->unique();         // admin, cashier, kitchen
            $table->string('display_name_ar');            // مدير, كاشير, المطبخ
            $table->string('display_name_en');            // مدير, كاشير, المطبخ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
