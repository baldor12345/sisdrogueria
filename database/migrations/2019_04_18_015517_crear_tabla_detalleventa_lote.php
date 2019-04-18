<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleventaLote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleventa_lote', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad')->nullable();
            $table->integer('entrada_id')->unsigned();
            $table->integer('detalle_venta_id')->unsigned();
            $table->foreign('detalle_venta_id')->references('id')->on('detalle_ventas')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('entrada_id')->references('id')->on('entrada')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('detalleventa_lote');
    }
}
