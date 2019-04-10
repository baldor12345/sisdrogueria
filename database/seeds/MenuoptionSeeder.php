<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuoptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Usuarios')->first()->id;

		$datos = array(
				array(
					'name' => 'Categoría de opción de menu',
					'link'   => 'categoriaopcionmenu'
				),
				array(
					'name' => 'Opción de menu',
					'link'   => 'opcionmenu'
				),
				array(
					'name' => 'Tipos de usuario',
					'link'   => 'tipousuario'
				),
				array(
					'name' => 'Usuario',
					'link'   => 'usuario'
				)
			);

		for ($i=0; $i < count($datos); $i++) { 
			DB::table('menuoption')->insert(array(
					'name'                 => $datos[$i]['name'],
					'link'                   => $datos[$i]['link'],
					'order'                  => $i+1,
					'menuoptioncategory_id' => $menuoptioncategory_id,
					'created_at'             => $now,
					'updated_at'             => $now
				)
			);
		}

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Mantenimientos')->first()->id;

		$datos = array(
			array(
				'name' => 'Propiedades',
				'link'   => 'propiedades'
			),
			array(
				'name' => 'Sucursales',
				'link'   => 'sucursal'
			),
			array(
				'name' => 'Proveedores',
				'link'   => 'proveedor'
			),
			array(
				'name' => 'Categoria',
				'link'   => 'categoria'
			),
			array(
				'name' => 'Marca/Laboratorio',
				'link'   => 'marca'
			),
			array(
				'name' => 'Unidades',
				'link'   => 'unidad'
			),
			array(
				'name' => 'Productos',
				'link'   => 'producto'
			)
			);

		for ($i=0; $i < count($datos); $i++) { 
			DB::table('menuoption')->insert(array(
					'name'                 => $datos[$i]['name'],
					'link'                   => $datos[$i]['link'],
					'order'                  => $i+1,
					'menuoptioncategory_id' => $menuoptioncategory_id,
					'created_at'             => $now,
					'updated_at'             => $now
				)
			);
		}

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Personas')->first()->id;

		$datos = array(
				array(
					'name' => 'Tipo personal',
					'link'   => 'tipopersona'
				),
				array(
					'name' => 'Clientes',
					'link'   => 'clientes'
				),
				array(
					'name' => 'Trabajadores',
					'link'   => 'trabajador'
				),
				array(
					'name' => 'Departamentos',
					'link'   => 'departamento'
				),
				array(
					'name' => 'Provincias',
					'link'   => 'provincia'
				),
				array(
					'name' => 'Distritos',
					'link'   => 'distrito'
				)
			);

		for ($i=0; $i < count($datos); $i++) { 
			DB::table('menuoption')->insert(array(
					'name'                 => $datos[$i]['name'],
					'link'                   => $datos[$i]['link'],
					'order'                  => $i+1,
					'menuoptioncategory_id' => $menuoptioncategory_id,
					'created_at'             => $now,
					'updated_at'             => $now
				)
			);
		}

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Movimientos')->first()->id;

		$datos = array(
				array(
					'name' => 'Caja',
					'link'   => 'caja'
				),
				array(
					'name' => 'Ventas',
					'link'   => 'ventas'
				),
				array(
					'name' => 'Reportes',
					'link'   => 'reportes'
				)
			);

		for ($i=0; $i < count($datos); $i++) { 
			DB::table('menuoption')->insert(array(
					'name'                 => $datos[$i]['name'],
					'link'                   => $datos[$i]['link'],
					'order'                  => $i+1,
					'menuoptioncategory_id' => $menuoptioncategory_id,
					'created_at'             => $now,
					'updated_at'             => $now
				)
			);
		}
    }
}
