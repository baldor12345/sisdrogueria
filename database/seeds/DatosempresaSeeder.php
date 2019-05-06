<?php

use Illuminate\Database\Seeder;

class DatosempresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('datos_empresa')->insert(array(
            array(
                'ruc' => '20604159360',
                'razon_social' => 'OSTEOMEDIC PERÚ E.I.R.L',
                'direccion' => 'CAL. SAN JOSE 387 DPTO. 103 CERCADO DE CHICLAYO',
                'distrito_id' => 2,
                'provincia_id' => 2,
                'departamento_id' => 1,
                'created_at'     => $now,
                'updated_at' => $now
            )
        ));
    }
}
