<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProveedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);//empresa tal
            $table->string('direccion', 100)->nullable();
            $table->string('ruc', 100)->nullable();
            $table->string('persona_contacto', 100);
            $table->string('telefono', 14)->nullable();
            $table->string('celular', 14)->nullable();
            $table->char('estado', 1)->nullable();
            $table->integer('distrito_id')->unsigned();
            $table->foreign('distrito_id')->references('id')->on('distrito')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('proveedor');
    }
}
