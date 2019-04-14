<?php

use Illuminate\Database\Seeder;

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('forma_pago')->insert(array(
            array(
                'nombre' => 'Tarjeta Credito',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'Efectivo',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'Paypal',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'Skrill',
                'created_at' => $now,
                'updated_at' => $now
            ),
           
            array(
                'nombre' => 'Neteller',
                'created_at' => $now,
                'updated_at' => $now
            ),
           
            array(
                'nombre' => 'Perfect Money',
                'created_at' => $now,
                'updated_at' => $now
            ),
            array(
                'nombre' => 'Bitcoin',
                'created_at' => $now,
                'updated_at' => $now
            ),
           
            
        ));
    }
}
