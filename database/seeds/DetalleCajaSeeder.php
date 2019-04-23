
<?php

use Illuminate\Database\Seeder;

class DetalleCajaSeeder extends Seeder
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
		DB::table('detalle_caja')->insert(array(
            array(
                'fecha' => $now,
                'numero_operacion' => '00000001',
                'concepto_id' => 1,
                'ingreso' => 0,
                'egreso' => 0,
                'estado' => 'C',
                'forma_pago' => 'C',
                'caja_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
