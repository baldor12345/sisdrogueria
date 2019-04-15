<?php

use Illuminate\Database\Seeder;

class PresentacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('presentacion')->insert(array(
            array(
                'nombre' => 'CAJA',
                'sigla' => 'caja',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'BLISTER',
                'sigla' => 'blister',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'UNIDAD',
                'sigla' => 'unidad',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'KILOGRAMO',
                'sigla' => 'kg',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'LITRO',
                'sigla' => 'lt',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'GRAMO',
                'sigla' => 'gr',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'MILIGRAMO',
                'sigla' => 'mg',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'MILIMOL',
                'sigla' => 'mml',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'MILILITRO',
                'sigla' => 'ml',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'MICROGRAMO',
                'sigla' => 'mcg',
                'created_at' => $now,
                'updated_at' => $now
            ),
        ));

    }
}
