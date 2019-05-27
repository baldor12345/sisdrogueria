<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('descuento',20, 2)->nullable();
            $table->decimal('subtotal',20, 3)->nullable();
            $table->decimal('total',20, 3)->nullable();
            $table->decimal('igv',20, 3)->nullable();
            $table->string('descripcion', 400)->nullable();
            $table->string('numero_operacion', 100)->nullable();//Numero autogqneardo que coincide con el numero operacion de caja
            // $table->string('codigo_venta', 10)->nullable();//codigo para busqueda rapida en la caja de pagos
            $table->string('serie_doc', 4)->nullable();//serie de documento factura o voleta
            $table->string('numero_doc', 8)->nullable();//numero documento de factura o voleta
            $table->timestamp('fecha')->nullable();
            $table->char('estado', 1)->nullable();//P=pendiente, E=Entregado
            $table->integer('user_id')->unsigned();
            $table->integer('caja_id')->unsigned();
            $table->integer('medico_id')->unsigned()->nullable();
            $table->integer('vendedor_id')->unsigned()->nullable();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->char('comprobante',1)->nullable();//V = Voleta, F = Factura
            $table->char('tipo_pago',2)->nullable();//CO=Contado, CR=Credito
            $table->char('forma_pago',1)->nullable();//E=Efectivo, T = Tarjeta
            $table->integer('dias')->nullable();//E=Efectivo, T = Tarjeta
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursal')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('caja_id')->references('id')->on('caja')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('cliente_id')->references('id')->on('cliente')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('medico_id')->references('id')->on('medico')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('vendedor_id')->references('id')->on('vendedor')->onDelete('restrict')->onUpdate('restrict');
            // $table->foreign('comprobante_id')->references('id')->on('comprobante')->onDelete('restrict')->onUpdate('restrict');
            // $table->foreign('forma_pago_id')->references('id')->on('forma_pago')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('ventas');
    }
}
