<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaEntrada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * tabla que se usa como inventario
     */
    public function up()
    {
        Schema::create('entrada', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha')->nullable();
            $table->timestamp('fecha_caducidad')->nullable();
            $table->char('fecha_completa')->nullable();//S=>SI, N=>NO
            $table->integer('stock')->nullable();
            $table->string('lote',100)->nullable();
            $table->integer('producto_presentacion_id')->unsigned()->nullable();
            $table->char('estado', 1)->nullable();//P=>Pendiente , C=>Cancelado
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('sucursal_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('producto_presentacion_id')->references('id')->on('producto_presentacion')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('entrada');
    }
}
