<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PersonSeeder::class);
        $this->call(MenuoptioncategorySeeder::class);
        $this->call(MenuoptionSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(DistritoSeeder::class);
        $this->call(SucursalSeeder::class);
        $this->call(UsertypeSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WorkertypeSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(UnidadSeeder::class);
        $this->call(PropiedadesSeeder::class);
        $this->call(ConceptoSeeder::class);
        $this->call(CajaSeeder::class);
        $this->call(PresentacionSeeder::class);

        $this->call(ComprobanteSeeder::class);
        $this->call(FormaPagoSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(ProductoSeeder::class);
        //$this->call(LaboratorioSeeder::class);

    }
}
