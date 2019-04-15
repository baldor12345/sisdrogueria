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
                'titulo' => 'Apertura de Caja',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'tipo' => 'E',
                'titulo' => 'Cierre de Caja',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'tipo' => 'I',
                'titulo' => 'Otros Ingresos',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'tipo' => 'E',
                'titulo' => 'Otros Egresos',
                'created_at' => $now,
                'updated_at' => $now
            ),
        ));
    }
}
