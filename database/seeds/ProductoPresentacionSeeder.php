<?php

use Illuminate\Database\Seeder;

class ProductoPresentacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('producto_presentacion')->insert(array(
            array(
                'producto_id' => 1,
                'presentacion_id' => 1,
                'precio_compra' => 20,
                'cant_unidad_x_presentacion' => 100,
                'precio_venta_unitario' => 0.50,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('producto_presentacion')->insert(array(
            array(
                'producto_id' => 1,
                'presentacion_id' => 2,
                'precio_compra' => 10,
                'cant_unidad_x_presentacion' => 10,
                'precio_venta_unitario' => 0.50,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('producto_presentacion')->insert(array(
            array(
                'producto_id' => 1,
                'presentacion_id' => 3,
                'precio_compra' => 0.20,
                'cant_unidad_x_presentacion' => 1,
                'precio_venta_unitario' => 0.50,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
