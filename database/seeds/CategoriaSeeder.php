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
                'name' => 'EN OSTEOPOROSIS',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'EN DOLOR',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'EN OSTEOARTROSIS',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'name' => 'EN ARTRITIS REUMATOIDE',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
