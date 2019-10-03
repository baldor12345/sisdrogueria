<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiaRemisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_remisions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha_emision')->nullable();
            $table->timestamp('fecha_inicio_traslado')->nullable();
            $table->string('motivo_traslado', 600)->nullable();
            $table->string('modalidad_transporte', 400)->nullable();
            $table->string('pesobruto_total', 20)->nullable();
            $table->string('nombres_destinatario', 200)->nullable();
            $table->string('doc_identidad', 20)->nullable();
            $table->string('direccion_partida', 200)->nullable();
            $table->string('direccion_llegada', 200)->nullable();
            $table->string('numero_placa', 20)->nullable();
            $table->string('tipodoc_conductor', 20)->nullable();
            $table->string('numerodoc_conductor', 20)->nullable();
            $table->string('observaciones', 400)->nullable();
            $table->string('serie', 20)->nullable();
            $table->string('numero', 20)->nullable();
            $table->integer('sucursal_id')->unsigned();
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
        Schema::dropIfExists('guia_remisions');
    }
}
