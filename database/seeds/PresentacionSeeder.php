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
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'BLISTER',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'UNIDAD',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));

    }
}
