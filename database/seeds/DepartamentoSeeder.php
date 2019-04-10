<?php

use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('departamento')->insert(array(
            'id'=> 1,
            'nombre' =>'Lambayeque',
            'created_at'     => $now,
            'updated_at'     => $now
        ));
    }
}
