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
				),
				array(
					'name' => 'Empresa',
					'link'   => 'datosempresa'
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
				'name' => 'Conceptos',
				'link'   => 'concepto'
			),
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
				'name' => 'Productos',
				'link'   => 'producto'
			),
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
					'name' => 'Médicos',
					'link'   => 'medico'
				),
				array(
					'name' => 'Vendedor',
					'link'   => 'vendedor'
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
					'name' => 'Productos Vendidos',
					'link'   => 'productoventa'
				),
				array(
					'name' => 'Puntos Acumulados',
					'link'   => 'puntos_medicos'
				),
				array(
					'name' => 'Guia de Remisión',
					'link'   => 'guias'
				),
				array(
					'name' => 'Nota de Crédito',
					'link'   => 'notacreditos'
				),
				/*
				array(
					'name' => 'Devoluciones',
					'link'   => 'devoluciones'
				)*/
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
					'name' => 'Caja Diaria',
					'link'   => 'caja'
				),
				array(
					'name' => 'Pendientes',
					'link'   => 'pendientes'
				),
				/*
				array(
					'name' => 'Mov. Anulados',
					'link'   => 'anulados'
				)*/
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

		$menuoptioncategory_id = DB::table('menuoptioncategory')->where('name', '=', 'Mant. Almacen')->first()->id;

		$datos = array(
				array(
					'name' => 'Entradas y Salidas',
					'link'   => 'entrada_salida'
				),
				array(
					'name' => 'Lotes y Caducidad',
					'link'   => 'lotes_caducidad'
				),
				array(
					'name' => 'Stock',
					'link'   => 'stock_producto'
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
