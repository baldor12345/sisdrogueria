<?php

use Illuminate\Database\Seeder;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('comprobante')->insert(array(
            array(
                'nombre' => 'Voleta',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'Factura',
                'created_at' => $now,
                'updated_at' => $now
            ),
        ));
    }
}
