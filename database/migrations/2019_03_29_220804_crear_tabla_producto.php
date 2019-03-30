<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProducto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',100)->nullable();
            $table->string('nombre',100)->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('precio_venta',10,2)->nullable();
            $table->timestamp('fecha_llegada')->nullable();
            $table->timestamp('fecha_caducidad')->nullable();
            $table->string('sitio',100)->nullable();
            $table->string('descripcion',100)->nullable();
            $table->integer('marca_id')->unsigned()->nullable();
            $table->integer('unidad_id')->unsigned()->nullable();
            $table->integer('categoria_id')->unsigned()->nullable();
            $table->integer('laboratorio_id')->unsigned()->nullable();
            $table->integer('proveedor_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('marca_id')->references('id')->on('marca')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('unidad_id')->references('id')->on('unidad')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('categoria_id')->references('id')->on('categoria')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('laboratorio_id')->references('id')->on('laboratorio')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('proveedor_id')->references('id')->on('proveedor')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('producto');
    }
}
