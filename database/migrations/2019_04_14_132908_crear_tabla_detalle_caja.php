<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleCaja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_caja', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha')->nullable();
            $table->string('numero_operacion', 400)->nullable();
            $table->integer('concepto_id')->unsigned()->nullable();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->decimal('ingreso',20, 3);
            $table->decimal('egreso',20, 3)->nullable();
            $table->integer('forma_pago_id')->unsigned();
            $table->char('estado', 1)->nullable();// A=>abierto; C=>cerrado
            $table->string('comentario', 400)->nullable();
            $table->integer('caja_id')->unsigned()->nullable();
            $table->integer('comprobante_id')->unsigned()->nullable();
            $table->foreign('cliente_id')->references('id')->on('cliente')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('caja_id')->references('id')->on('caja')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('concepto_id')->references('id')->on('concepto')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('detalle_caja');
    }
}
