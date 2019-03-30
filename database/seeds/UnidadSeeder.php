<?php

use Illuminate\Database\Seeder;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('unidad')->insert(array(
            array(
                'name' => 'TABLETAS',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'UNIDAD',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'FRASCO',
                'created_at' => $now,
                'updated_at' => $now
            )
		));
    }
}
