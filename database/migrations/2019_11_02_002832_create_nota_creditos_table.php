<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('descuento',20, 2)->nullable();
            $table->decimal('subtotal',20, 3)->nullable();
            $table->decimal('total',20, 3)->nullable();
            $table->decimal('igv',20, 3)->nullable();
            $table->string('serie_doc', 4)->nullable();//serie de documento factura o voleta
            $table->string('numero_doc', 8)->nullable();//numero documento de factura o voleta
            $table->timestamp('fecha')->nullable();
            $table->char('estado', 1)->nullable();//P=pendiente, E=Entregado
            $table->char('comprobante',1)->nullable();//V = Voleta, F = Factura
            $table->string('comentario',200)->nullable();//V = Voleta, F = Factura
            $table->integer('user_id')->unsigned();
            $table->integer('caja_id')->unsigned();
            $table->integer('medico_id')->unsigned()->nullable();
            $table->integer('vendedor_id')->unsigned()->nullable();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('caja_id')->references('id')->on('caja')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('cliente_id')->references('id')->on('cliente')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('medico_id')->references('id')->on('medico')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('vendedor_id')->references('id')->on('vendedor')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('nota_creditos');
    }
}
