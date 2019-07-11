<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

		$now = new DateTime;
		DB::table('person')->insert(array(
               
                'nombres'       =>'Administrador1',
                'apellidos'       =>'admin',
				'created_at'     => $now,
				'updated_at'     => $now
        ));
		DB::table('person')->insert(array(
               
                'nombres'       =>'Administrador2',
                'apellidos'       =>'admin',
				'created_at'     => $now,
				'updated_at'     => $now
        ));

    }
}