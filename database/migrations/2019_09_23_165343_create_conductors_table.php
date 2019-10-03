<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConductorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conductors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_doc', 50)->nullable();
            $table->string('numero_doc', 20)->nullable();
            $table->string('nombres', 100)->nullable();
            $table->string('apellidos', 100)->nullable();
            $table->integer('guiaremision_id')->unsigned();
            $table->foreign('guiaremision_id')->references('id')->on('guia_remisions')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('conductors');
    }
}
