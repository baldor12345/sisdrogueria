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
            array(
                'id'=> 1,
                'nombre' =>'LAMBAYEQUE',
                'departamento_id' => 1,
                'created_at'     => $now,
                'updated_at'     => $now
            ),
            array(
                'id'=> 2,
                'nombre' =>'CHICLAYO',
                'departamento_id' => 1,
                'created_at'     => $now,
                'updated_at'     => $now
            ),
        ));
    }
}
