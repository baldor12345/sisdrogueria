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
                'name' => 'LABORATORIO 001',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'LABORATORIO 002',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'LABORATORIO 003',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
