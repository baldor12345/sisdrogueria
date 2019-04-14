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
				'name' => 'Presentacion',
				'link'   => 'presentacion'
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
			),
			array(
				'name' => 'Comprobantes',
				'link'   => 'comprobante'
			),
			array(
				'name' => 'Formas de pago',
				'link'   => 'forma_pago'
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

		//compras
		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Compras')->first()->id;

		$datos = array(
				array(
					'name' => 'Compras',
					'link'   => 'compra'
				),
				array(
					'name' => 'Lotes y Caducidad',
					'link'   => 'lotes_caducidad'
				),
				array(
					'name' => 'Stock',
					'link'   => 'stock'
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

		//ventas
		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Ventas')->first()->id;

		$datos = array(
				array(
					'name' => 'Ventas',
					'link'   => 'ventas'
				),
				array(
					'name' => 'Productos mas Vendidas',
					'link'   => 'productos_mas_vendidas'
				),
				array(
					'name' => 'Devoluciones',
					'link'   => 'devoluciones'
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
	

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Caja')->first()->id;

		$datos = array(
				array(
					'name' => 'Caja',
					'link'   => 'caja'
				),
				array(
					'name' => 'Caja Diaria',
					'link'   => 'ventas'
				),
				array(
					'name' => 'Mov. Anulados',
					'link'   => 'anulados'
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
