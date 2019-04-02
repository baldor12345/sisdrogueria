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
					'name'     => 'Movimientos',
					'order'      => 1,
					'icon'      => 'fa fa-bank',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Mantenimientos',
					'order'      => 2,
					'icon'      => 'fa fa-bank',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Personas',
					'order'      => 3,
					'icon'      => 'fa fa-bank',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				),
				array(
					'name'     => 'Usuarios',
					'order'      => 4,
					'icon'      => 'fa fa-bank',
					'position'      => 'V',
					'created_at' => $now,
					'updated_at' => $now
				)
			)
		);
    }
}
