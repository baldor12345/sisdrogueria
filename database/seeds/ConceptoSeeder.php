<?php

use Illuminate\Database\Seeder;

class ConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('concepto')->insert(array(
            array(
                'tipo' => 'I',
                'nombre' => 'Apertura de Caja',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'tipo' => 'E',
                'nombre' => 'Cierre de Caja',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'tipo' => 'I',
                'nombre' => 'Otros Ingresos',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'tipo' => 'E',
                'nombre' => 'Otros Egresos',
                'created_at' => $now,
                'updated_at' => $now
            ),
        ));
    }
}
