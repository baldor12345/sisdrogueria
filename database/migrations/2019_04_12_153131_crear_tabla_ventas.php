<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('descuento',20, 2)->nullable();
            $table->decimal('total',20, 2)->nullable();
            $table->decimal('igv',20, 2)->nullable();
            $table->string('descripcion', 400)->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->char('estado', 1)->nullable();//P=pendiente, E=Entregado
            $table->integer('user_id')->unsigned();
            $table->integer('caja_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->integer('comprobante_id')->unsigned()->nullable();
            $table->integer('forma_pago_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('caja_id')->references('id')->on('caja')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('cliente_id')->references('id')->on('cliente')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('comprobante_id')->references('id')->on('comprobante')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('forma_pago_id')->references('id')->on('forma_pago')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('ventas');
    }
}
