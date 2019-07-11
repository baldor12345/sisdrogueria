<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        $user1           = DB::table('user')->where('login','adminA')->get();
		DB::table('caja')->insert(array(
            array(
                'num_caja' => 'CSA0001',
                'fecha_horaapert' => $now,
                'monto_iniciado' => 0.0,
                'estado' => 'A',
                'user_id' => $user1[0]->id,
                'sucursal_id' => $user1[0]->sucursal_id,
                'created_at' => $now,
                'updated_at' => $now,
                'updated_at' => $now
            )
        ));
        $user2           = DB::table('user')->where('login','adminB')->get();
		DB::table('caja')->insert(array(
            array(
                'num_caja' => 'CSB0001',
                'fecha_horaapert' => $now,
                'monto_iniciado' => 0.0,
                'estado' => 'A',
                'user_id' => $user2[0]->id,
                'sucursal_id' => $user2[0]->sucursal_id,
                'created_at' => $now,
                'updated_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
