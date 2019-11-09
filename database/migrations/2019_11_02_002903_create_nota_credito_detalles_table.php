<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaCreditoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_credito_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad')->nullable();
            $table->decimal('precio_unitario',20, 2)->nullable();
            $table->decimal('descuento',20, 2)->nullable();
            $table->decimal('total',20, 2)->nullable();
            $table->string('lotes', 100)->nullable();
            $table->integer('producto_presentacion_id')->unsigned();
            $table->integer('nota_credito_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('puntos_acumulados')->nullable();
            $table->foreign('nota_credito_id')->references('id')->on('nota_creditos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('nota_credito_detalles');
    }
}
