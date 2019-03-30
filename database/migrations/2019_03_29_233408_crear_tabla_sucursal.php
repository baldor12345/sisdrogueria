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
            $table->string('direccion',100);
            $table->integer('distrito_id')->unsigned();
            $table->foreign('distrito_id')->references('id')->on('distrito')->onUpdate('restrict')->onDelete('restrict');
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
