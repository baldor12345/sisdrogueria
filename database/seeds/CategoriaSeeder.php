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
                'name' => 'Ampolla',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Capsulas',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Crema',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Efervecente',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Gel',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Gotas',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
