<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleSalida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_salida', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha_caducidad')->nullable();
            $table->decimal('precio_compra',10,2)->nullable();//precio por la unidad de medida
            $table->decimal('precio_venta',10,2)->nullable();//precio al publico
            $table->integer('stock')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('lote',100)->nullable();
            $table->integer('producto_id')->unsigned()->nullable();
            $table->integer('salida_id')->unsigned()->nullable();
            $table->integer('presentacion_id')->unsigned()->nullable();
            $table->integer('marca_id')->unsigned()->nullable();
            $table->foreign('producto_id')->references('id')->on('producto')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('salida_id')->references('id')->on('salida')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('presentacion_id')->references('id')->on('presentacion')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('marca_id')->references('id')->on('marca')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('detalle_salida');
    }
}
