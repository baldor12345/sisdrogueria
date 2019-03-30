<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetallePerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_person', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha_salida')->nullable();
            $table->timestamp('fecha_ingreso')->nullable();
            $table->integer('person_id')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->foreign('person_id')->references('id')->on('person')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onUpdate('restrict')->onDelete('restrict');
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
        Schema::dropIfExists('detalle_person');
    }
}
