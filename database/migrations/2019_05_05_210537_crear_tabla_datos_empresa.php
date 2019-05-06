<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDatosEmpresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_empresa', function (Blueprint $table) {
            $table->increments('id');
           
            $table->string('ruc', 20)->nullable();
            $table->string('razon_social', 100)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 12)->nullable();
            $table->string('email', 50)->nullable();
            $table->integer('distrito_id')->unsigned();
            $table->integer('provincia_id')->unsigned();
            $table->integer('departamento_id')->unsigned();
            $table->foreign('distrito_id')->references('id')->on('distrito')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('provincia_id')->references('id')->on('provincia')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('departamento_id')->references('id')->on('departamento')->onUpdate('restrict')->onDelete('restrict');
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
        Schema::dropIfExists('datos_empresa');
    }
}
