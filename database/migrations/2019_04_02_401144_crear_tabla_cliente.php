<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres',100)->nullable();
            $table->string('apellidos',100)->nullable();
            $table->char('dni',8)->nullable();
            $table->char('ruc',20)->nullable();
            $table->string('razon_social',100)->nullable();
            $table->string('direccion',120)->nullable();
            $table->string('telefono',15)->nullable();
            $table->string('celular',15)->nullable();
            $table->string('email',30)->nullable();
            $table->char('estado',1)->nullable();
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
        Schema::dropIfExists('cliente');
    }
}
