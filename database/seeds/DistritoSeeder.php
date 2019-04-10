<?php

use Illuminate\Database\Seeder;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('distrito')->insert(array(
            'id'=> 1,
            'nombre' =>'Lambayeque',
            'provincia_id' => 1,
            'created_at'     => $now,
            'updated_at'     => $now
        ));
    }
}
