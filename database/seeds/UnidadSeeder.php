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
                'name' => 'Tabletas',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Unidad',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Frasco',
                'created_at' => $now,
                'updated_at' => $now
            )
		));
    }
}
