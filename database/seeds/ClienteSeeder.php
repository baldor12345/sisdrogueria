<?php

use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('cliente')->insert(array(
            array(
                'dni' => '00000001',
                'nombres' => 'Varios',
                'apellidos' => '',
                // 'ruc' => '',
                // 'razon_social' => '',
                // 'direccion' => '',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
