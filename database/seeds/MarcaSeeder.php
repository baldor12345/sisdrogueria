<?php

use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('marca')->insert(array(
            array(
                'name' => 'Paracetamol',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Atropina',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Baclofeno',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Cefotaxima',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Cetirizina',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'Cloroquina',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
