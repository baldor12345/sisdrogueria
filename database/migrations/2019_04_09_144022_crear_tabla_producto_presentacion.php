<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProductoPresentacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_presentacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('producto_id')->unsigned()->nullable();//MG LTRS,
            $table->integer('presentacion_id')->unsigned()->nullable();//MG LTRS,
            $table->integer('cant_unidad_x_presentacion');
            $table->decimal('precio_compra')->nullable();
            $table->decimal('precio_venta_unitario',10,2)->nullable();
            $table->foreign('producto_id')->references('id')->on('producto')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('presentacion_id')->references('id')->on('presentacion')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('producto_presentacion');
    }
}
