<?php

use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('provincia')->insert(array(
            'id'=> 1,
            'nombre' =>'Lambayeque',
            'departamento_id' => 1,
            'created_at'     => $now,
            'updated_at'     => $now
        ));
    }
}
