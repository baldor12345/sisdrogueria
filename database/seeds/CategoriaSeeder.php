<?php

use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('categoria')->insert(array(
            array(
                'name' => 'AMPOLLA',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'CAPSULAS',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'CREMA',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'EFERVECENTE',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'GEL',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'GOTAS',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
