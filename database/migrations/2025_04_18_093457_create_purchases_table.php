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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();      
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('purchases_date')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('total_payment')->nullable();
            $table->integer('total_change')->nullable();
            $table->integer('poin');
            $table->integer('total_poin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
