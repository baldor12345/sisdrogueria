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
            $table->decimal('precio_compra',10,2)->nullable();//precio por la unidad de medida
            $table->decimal('precio_venta',10,2)->nullable();//precio al publico
            $table->integer('stock')->nullable();
            $table->string('lote',100)->nullable();
            $table->integer('producto_presentacion_id')->unsigned()->nullable();
            $table->integer('presentacion_id')->unsigned()->nullable();

            $table->char('estado', 1)->nullable();//P=>Pendiente , C=>Cancelado
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('sucursal_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('presentacion_id')->references('id')->on('presentacion')->onDelete('restrict')->onUpdate('restrict');
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
