<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBienesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bienes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad')->unsigned();
            $table->string('lotes', 100)->nullable();
            $table->integer('producto_presentacion_id')->unsigned();
            $table->integer('guiaremision_id')->unsigned();
            $table->foreign('producto_presentacion_id')->references('id')->on('producto_presentacion')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('bienes');
    }
}
