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
        $user           = DB::table('user')->where('login','admin')->get();
		DB::table('caja')->insert(array(
            array(
                'num_caja' => '0001',
                'fecha_horaapert' => $now,
                'monto_iniciado' => 0.0,
                'estado' => 'A',
                'user_id' => $user[0]->id,
                'sucursal_id' => $user[0]->sucursal_id,
                'created_at' => $now,
                'updated_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
