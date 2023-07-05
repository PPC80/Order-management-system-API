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
        Schema::create('cart_details', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('id_cart');
            $table->foreign('id_cart')
                    ->references('id')
                    ->on('carts')
                    ->onUpdate('cascade');
            $table->unsignedBigInteger('id_producto');
            $table->foreign('id_producto')
                    ->references('id')
                    ->on('products')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->smallInteger('cantidad')->unsigned();
            $table->decimal('suma_precio', 7, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};
