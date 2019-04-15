<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaSalida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salida', function (Blueprint $table) {
            $table->increments('id');
            $table->char('documento', 1)->nullable();//0=>FACTURA DE COMPRA, 1=>BOLETA DE COMPRA, 2=>GUIA INTERNA   , 3=>NOTA DE CREDITO, 4=>TICKET
            $table->char('tipo', 1)->nullable();//ENTRADA, SALIDA
            $table->timestamp('fecha')->nullable();
            $table->string('numero_documento',100)->nullable();
            $table->string('comentario',400)->nullable();

            $table->char('estado', 1)->nullable();//P=>Pendiente , C=>Cancelado
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('sucursal_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salida');
    }
}
