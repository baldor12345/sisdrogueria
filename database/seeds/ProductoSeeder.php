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
                'codigo' => '00001',
                'codigo_barra' => '',
                'descripcion' => 'ZELEDRON',
                'sustancia_activa' => 'Ácido Zoledrónico 5mg/100ml Solucion Inyectable Caja por 1 frasco x 100mL',
                'uso_terapeutico' => '',
                'tipo' => 1,
                'afecto' => 'S',
                'marca_id' => 1,
                'ubicacion' => 'STAND 001',
                'categoria_id' => 1,
                'stock_minimo' => 2,
                'estado' => 'A',
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
