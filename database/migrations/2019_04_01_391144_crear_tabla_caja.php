<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCaja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('num_caja')->nullable();
            $table->string('descripcion', 400)->nullable();
            $table->timestamp('fecha_horaapert')->nullable();
            $table->timestamp('fecha_horacierre')->nullable();
            $table->decimal('monto_iniciado',20, 7);
            $table->decimal('monto_cierre',20, 7)->nullable();
            $table->char('estado', 1)->nullable();// A=>abierto; C=>cerrado
            $table->integer('user_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('caja');
    }
}
