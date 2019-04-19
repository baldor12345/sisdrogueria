<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra', function (Blueprint $table) {
            $table->increments('id');
            $table->char('documento', 1)->nullable();//0=>FACTURA DE COMPRA, 1=>BOLETA DE COMPRA, 2=>GUIA INTERNA   , 3=>NOTA DE CREDITO, 4=>TICKET
            $table->string('numero_documento',100)->nullable();
            $table->string('serie_documento',100)->nullable();
            $table->char('tipo_pago', 2)->nullable();//CO=>CONTADO , CR=>CREDITO
            $table->integer('numero_dias')->nullable();
            $table->string('ruc',100)->nullable();
            $table->integer('proveedor_id')->unsigned()->nullable();
        
            $table->char('estado', 1)->nullable();//P=>Pendiente , C=>Cancelado
            $table->timestamp('fecha')->nullable();
            $table->timestamp('fecha_caducidad')->nullable();
            
            $table->decimal('total',10,2)->nullable();//precio por la unidad de medida
            $table->decimal('igv',10,2)->nullable();//precio por la unidad de medida
            
            $table->integer('user_id')->unsigned()->nullable();
            //$table->integer('caja_id')->unsigned()->nullable();
            $table->integer('sucursal_id')->unsigned()->nullable();
            $table->foreign('proveedor_id')->references('id')->on('proveedor')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            //$table->foreign('caja_id')->references('id')->on('caja')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('compra');
    }
}
