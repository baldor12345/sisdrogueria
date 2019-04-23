<?php

use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

        DB::table('sucursal')->insert(array(
            [
                'id'=> 1,
                'nombre' =>'Sucursal_A',
                'telefono' =>'930532414',
                'direccion' =>'rivadeneyra',
                'distrito_id' =>1,
                'provincia_id' =>1,
                'departamento_id' =>1,
                'created_at'     => $now,
                'updated_at'     => $now
            ],
            [
                'id'=> 2,
                'nombre' =>'Sucursal_B',
                'telefono' =>'930531212',
                'direccion' =>'juan fanning',
                'distrito_id' =>1,
                'provincia_id' =>1,
                'departamento_id' =>1,
                'created_at'     => $now,
                'updated_at'     => $now
            ]
        ));
    }
}
