<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaLote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',100)->nullable();
            $table->timestamp('fecha_inicial')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->char('estado', 1)->nullable();// check
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
        Schema::dropIfExists('lote');
    }
}
