<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaSucursal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100);
            $table->string('telefono',15);
            $table->string('direccion',100);
            $table->string('serie',4)->nullable();
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
        Schema::dropIfExists('sucursal');
    }
}

