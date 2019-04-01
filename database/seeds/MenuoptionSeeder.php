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

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Administración')->first()->id;

		$datos = array(
			array(
				'name' => 'Sucursales',
				'link'   => 'sucursal'
			),
			array(
				'name' => 'Proveedores',
				'link'   => 'proveedor'
			),
			array(
				'name' => 'Categoria/Presentacion',
				'link'   => 'categoria'
			),
			array(
				'name' => 'Marca/Laboratorio',
				'link'   => 'marca'
			),
			array(
				'name' => 'Unidades de Medida',
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
					'name' => 'Tipo trabajador',
					'link'   => 'tipotrabajador'
				),
				array(
					'name' => 'Clientes',
					'link'   => 'clientes'
				),
				array(
					'name' => 'Trabajadores',
					'link'   => 'trabajadores'
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
    }
}
