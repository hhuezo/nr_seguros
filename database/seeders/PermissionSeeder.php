<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $acciones = ['read', 'create', 'edit', 'delete'];

        $estructura = [
            // ID 1: Administracion
            1 => [
                'prefijo' => 'administracion',
                'modulos' => ['control-primas'] // Aquí incluimos el permiso que pediste
            ],
            // ID 2: Seguridad
            2 => [
                'prefijo' => 'seguridad',
                'modulos' => ['usuario', 'permiso', 'tipo-permiso', 'rol']
            ],
            // ID 3: Configuracion
            3 => [
                'prefijo' => 'configuracion',
                'modulos' => ['configuracion-recibo', 'numeracion-recibo']
            ],
            // ID 4: Catalogos
            4 => [
                'prefijo' => 'catalogos',
                'modulos' => [
                    'ejecutivos',
                    'estado-polizas',
                    'estado-venta',
                    'nr-cartera',
                    'tipo-negocio',
                    'tipo-cobro',
                    'tipo-poliza',
                    'area-comercial',
                    'ubicacion-cobro',
                    'ramo',
                    'perfiles',
                    'departamento-nr',
                    'producto',
                    'plan'
                ]
            ],
            // ID 5: Suscripciones
            5 => [
                'prefijo' => 'suscripciones',
                'modulos' => [
                    'suscripcion',
                    'fechas-feriadas',
                    'estados-casos',
                    'tipos-ordenes',
                    'tipos-imc',
                    'tipos-clientes',
                    'ocupaciones',
                    'tipo-creditos'
                ]
            ],
            // ID 6: Catalogo Deuda
            6 => [
                'prefijo' => 'catalogo-deuda',
                'modulos' => ['linea-credito']
            ],
            // ID 7: Catalogo Vida
            7 => [
                'prefijo' => 'catalogo-vida',
                'modulos' => ['tipo-cartera-vida']
            ],
            // ID 8: Cliente
            8 => [
                'prefijo' => 'cliente',
                'modulos' => ['gestion-cliente']
            ],
            // ID 9: Cotizaciones
            9 => [
                'prefijo' => 'cotizaciones',
                'modulos' => ['negocio']
            ],
            // ID 10: Aseguradoras
            10 => [
                'prefijo' => 'aseguradoras',
                'modulos' => ['gestion-aseguradora']
            ],
            // ID 11: Polizas
            11 => [
                'prefijo' => 'polizas',
                'modulos' => ['residencia', 'vida', 'deuda', 'desempleo', 'seguro', 'control-cartera']
            ]
        ];

        foreach ($estructura as $typeId => $datos) {
            $prefijo = $datos['prefijo'];

            // 1. Crear el permiso del MENU principal (ej: administracion-menu)
            Permission::updateOrCreate(
                ['name' => "$prefijo menu"],
                ['permission_type_id' => $typeId]
            );

            // 2. Crear los permisos CRUD para cada SUBMENU / MODULO
            foreach ($datos['modulos'] as $modulo) {
                foreach ($acciones as $accion) {
                    Permission::updateOrCreate(
                        [
                            //'name' => "$prefijo-$modulo-$accion"

                            'name' => "$modulo $accion"
                        ],
                        ['permission_type_id' => $typeId]
                    );
                }
            }
        }


        $role = Role::findOrFail(1);
        $role->givePermissionTo(Permission::all());

        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole(1);
        }
    }
}
