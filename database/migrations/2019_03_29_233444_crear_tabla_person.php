<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

Schema::create('person', function (Blueprint $table) {
    $table->increments('id');
    $table->string('nombres',100)->nullable();
    $table->string('apellidos',100)->nullable();
    $table->char('dni',8)->nullable();
    $table->char('ruc',11)->nullable();
    $table->string('direccion',120)->nullable();
    $table->string('telefono',15)->nullable();
    $table->string('celular',15)->nullable();
    $table->string('email',30)->nullable();
    $table->date('fecha_nacimiento')->nullable();
    $table->text('observacion')->nullable();
    $table->char('estado',1)->nullable();//A=Activo, I=Inactivo
    $table->integer('tipo_persona_id')->unsigned()->nullable();
    $table->foreign('tipo_persona_id')->references('id')->on('tipo_persona')->onUpdate('restrict')->onDelete('restrict');
    // $table->integer('sucursal_id')->unsigned()->nullable(); 
    // $table->foreign('sucursal_id')->references('id')->on('sucursal')->onUpdate('restrict')->onDelete('restrict');
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
        Schema::dropIfExists('person');
    }
}
