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
            $table->string('codigo_barra',100)->nullable();
            $table->string('descripcion',100)->nullable();//ACAROBOSA 50MG 30 TABLETAS
            $table->string('sustancia_activa',100)->nullable();//ACAROBOSA, 
            $table->string('uso_terapeutico',100)->nullable();//ANTHIPERTENSIVO, PARA PRESION ALTA
            $table->char('tipo', 1)->nullable();//0=>SIN ESPECIFICAR, 1=>GENERICO, 2=>OTROS, 3=>PATENTE, 4=>SIMILAR
            $table->integer('proveedor_id')->unsigned()->nullable();
            $table->integer('marca_id')->unsigned()->nullable();//MARCA O LABORATORIO
            $table->string('ubicacion',100)->nullable();
        
            $table->integer('unidad_id')->unsigned()->nullable();//MG LTRS,
            $table->integer('categoria_id')->unsigned()->nullable();//PRESENTACION(TABLETAS, AMPOLLAS, ETC)
            $table->integer('stock_minimo')->nullable();
        
            $table->char('estado', 1)->nullable();// check
        
            $table->decimal('costo',10,2)->nullable();//precio por la unidad de medida
            $table->decimal('precio_publico',10,2)->nullable();//precio al publico            
            
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('marca_id')->references('id')->on('marca')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('unidad_id')->references('id')->on('unidad')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('categoria_id')->references('id')->on('categoria')->onDelete('restrict')->onUpdate('restrict');
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
