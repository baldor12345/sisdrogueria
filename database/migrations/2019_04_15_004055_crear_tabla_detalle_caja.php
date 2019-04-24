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
            $table->string('codigo_operacion', 400)->nullable();
            $table->string('numero_operacion', 400)->nullable();
            $table->integer('concepto_id')->unsigned()->nullable();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->decimal('ingreso',20, 3);
            $table->decimal('egreso',20, 3)->nullable();
            $table->char('estado', 1)->nullable();//C=>Cancelado, P=>Pendiente
            $table->char('forma_pago', 2)->nullable();
            $table->string('comentario', 400)->nullable();
            $table->integer('caja_id')->unsigned()->nullable();
            $table->foreign('cliente_id')->references('id')->on('cliente')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('caja_id')->references('id')->on('caja')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('concepto_id')->references('id')->on('concepto')->onDelete('restrict')->onUpdate('restrict');
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
