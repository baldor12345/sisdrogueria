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
                'name' => 'KILOGRAMO',
                'simbolo' => 'kg',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'LITRO',
                'simbolo' => 'lt',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'GRAMO',
                'simbolo' => 'gr',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'MILIGRAMO',
                'simbolo' => 'mg',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'MILIMOL',
                'simbolo' => 'mml',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'MILILITRO',
                'simbolo' => 'ml',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'MICROGRAMO',
                'simbolo' => 'mcg',
                'created_at' => $now,
                'updated_at' => $now
            ),
		));
    }
}
