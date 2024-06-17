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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId')->nullable();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
           

            $table->unsignedBigInteger('productId')->nullable();
            $table->foreign('productId')->references('id')->on('products')->onDelete('set NULL');

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('productName')->nullable();
            $table->string('productImage')->nullable();
            $table->string('unitPrice')->nullable();
            $table->string('totalPrice')->nullable();
            $table->string('productQuantity')->nullable();


           
            // $table->foreign('userId')->references('id')->on('users')->onDelete('set NULL');
            


            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};