<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad')->nullable();
            $table->decimal('precio_unitario',20, 2)->nullable();
            $table->decimal('descuento',20, 2)->nullable();
            $table->string('descripcion', 400)->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->char('estado', 1)->nullable();// A=>abierto; C=>cerrado
            $table->integer('producto_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('producto_id')->references('id')->on('producto')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('venta');
    }
}
