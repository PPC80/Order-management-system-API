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
            $table->id()->index();
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')
                    ->references('id')
                    ->on('clients')
                    ->onUpdate('cascade');
            $table->enum('estado', ['pendiente', 'entregado']);
            $table->decimal('valor_total', 7, 2)->unsigned()->nullable();
            $table->enum('modo_pago', ['transferencia', 'PCE']);
            $table->unsignedBigInteger('id_direccion');
            $table->foreign('id_direccion')
                    ->references('id')
                    ->on('addresses')
                    ->onUpdate('cascade');
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
