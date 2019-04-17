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
                'presentacion_id' => 2,
                'cant_unidad_x_presentacion' => 10,
                'precio_venta_unitario' => 0.50,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
