<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaEntradaSalidaDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_salida_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha_caducidad')->nullable();
            $table->decimal('precio_compra',10,2)->nullable();//precio por la unidad de medida
            $table->decimal('precio_venta',10,2)->nullable();//precio al publico
            $table->integer('cantidad')->nullable();
            $table->string('lote',100)->nullable();
            $table->integer('entrada_salida_id')->unsigned()->nullable();
            $table->integer('producto_presentacion_id')->unsigned()->nullable();
            $table->foreign('entrada_salida_id')->references('id')->on('entrada_salida')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('entrada_salida_detalle');
    }
}
