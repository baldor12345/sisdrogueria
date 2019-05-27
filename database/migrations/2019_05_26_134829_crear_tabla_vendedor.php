<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaVendedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dni', 12)->nullable();
            $table->string('iniciales', 20)->nullable();
            $table->string('nombres', 50)->nullable();
            $table->string('apellidos', 100)->nullable();
            // $table->string('telefono', 100)->nullable();
            // $table->string('direccion', 100)->nullable();
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
        Schema::dropIfExists('vendedor');
    }
}
