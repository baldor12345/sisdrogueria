<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuoptioncategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('menuoptioncategory')->insert(array(
				array(
					'name'     => 'Caja',
					'order'      => 1,
					'icon'      => 'fa fa-money',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Compras',
					'order'      => 3,
					'icon'      => 'fa fa-bank',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Ventas',
					'order'      => 4,
					'icon'      => 'fa fa-bank',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Mant. Almacen',
					'order'      => 5,
					'icon'      => 'fa fa-cart-plus',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Mantenimientos',
					'order'      => 6,
					'icon'      => 'fa fa-gears',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Personas',
					'order'      => 7,
					'icon'      => 'fa fa-users',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Usuarios',
					'order'      => 8,
					'icon'      => 'fa fa-user',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				)
			)
		);
    }
}
