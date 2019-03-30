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
                'name' => 'PARACETAMOL',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'ATROPINA',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'BACLOFENO',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'CEFOTAXINA',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'CETIRISINA',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'CLOROQUINA',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
