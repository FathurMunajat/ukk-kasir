<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchases_id');
            $table->foreign('purchases_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('amount');
            $table->integer('price')->default(0);
            $table->integer('subtotal')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_purchases');
    }
};
