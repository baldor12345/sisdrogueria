<?php

use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('producto')->insert(array(
            array(
                'codigo' => 'AMOX211',
                'codigo_barra' => 'AMOX9284392',
                'descripcion' => 'AMOXICILINA(500mg tabletas-capsulas)',
                'sustancia_activa' => 'AMOXICILINA',
                'uso_terapeutico' => 'AMOXICILINA PARA DIARREA',
                'tipo' => 1,
                'proveedor_id' => 1,
                'marca_id' => 1,
                'ubicacion' => 'STAND 001',
                'presentacion_id' => 3,
                'categoria_id' => 2,
                'stock_minimo' => 2,
                'estado' => 'A',
                'costo' => 0.50,
                'precio_publico' => 0.80,
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
