<?php

use Illuminate\Database\Seeder;

class PropiedadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('propiedades')->insert(array(
            array(
                'num_decimales' => '2',
                'igv' => '0.18',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
