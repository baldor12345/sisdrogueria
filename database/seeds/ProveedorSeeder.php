<?php

use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('proveedor')->insert(array(
            array(
                'nombre' => 'SAN JUAN',
                'direccion' => 'JUNIN 231 LAMBAYEQUE',
                'ruc' => '10164090588',
                'persona_contacto' => 'JUAN PEREZ',
                'telefono' => '12312321',
                'celular' => '12312321',
                'estado' => 'A',
                'distrito_id' => 1,
                'provincia_id' => 1,
                'departamento_id' => 1,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
