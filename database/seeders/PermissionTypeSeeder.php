<?php

namespace Database\Seeders;

use App\Models\PermissionType;
use Illuminate\Database\Seeder;

class PermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Administracion',
            'Seguridad',
            'Configuracion',
            'catalogos',
            'suscripciones',
            'catalogo deuda',
            'catalogo vida',
            'cliente',
            'cotizaciones',
            'aseguradoras',
            'polizas',
        ];

        foreach ($tipos as $tipo) {
            PermissionType::updateOrCreate(
                ['name' => $tipo], // Busca por nombre
                ['active' => true]  // Si no existe lo crea activo, si existe lo deja activo
            );
        }
    }
}
