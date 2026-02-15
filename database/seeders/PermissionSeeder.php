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
        $this->call(PermissionTypeSeeder::class);

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
            // ID 11-16: Tipos de póliza (cada uno es un tipo de permiso)
            11 => ['prefijo' => 'poliza residencia', 'modulos' => ['residencia']],
            12 => ['prefijo' => 'poliza vida', 'modulos' => ['vida']],
            13 => ['prefijo' => 'poliza deuda', 'modulos' => ['deuda']],
            14 => ['prefijo' => 'poliza desempleo', 'modulos' => ['desempleo']],
            15 => ['prefijo' => 'poliza seguro', 'modulos' => ['seguro']],
            16 => ['prefijo' => 'poliza control-cartera', 'modulos' => ['control-cartera']],
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


        // Permisos de Deuda
        Permission::Create(['name' => 'deuda estado pago edit', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda estado pago view', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda estado pago annular', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda estado pago delete', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda estado pago export', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda aviso print', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda aviso edit', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda aviso export', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda historico pago view', 'permission_type_id' => 13]);
        Permission::Create(['name' => 'deuda historico pago export', 'permission_type_id' => 13]);

        // Permisos de Vida
        Permission::Create(['name' => 'vida estado pago edit', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida estado pago view', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida estado pago annular', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida estado pago delete', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida estado pago export', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida aviso print', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida aviso edit', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida historico pago view', 'permission_type_id' => 12]);
        Permission::Create(['name' => 'vida historico pago export', 'permission_type_id' => 12]);


        // Permisos de  residencia
        Permission::Create(['name' => 'residencia estado cobro edit', 'permission_type_id' => 11]);
        Permission::Create(['name' => 'residencia estado cobro export', 'permission_type_id' => 11]);
        Permission::Create(['name' => 'residencia estado cobro view', 'permission_type_id' => 11]);
        Permission::Create(['name' => 'residencia estado cobro delete', 'permission_type_id' => 11]);
        Permission::Create(['name' => 'residencia aviso print', 'permission_type_id' => 11]);
        Permission::Create(['name' => 'residencia aviso edit', 'permission_type_id' => 11]);

        //permios de desempleo
        Permission::Create(['name' => 'desempleo estado pago edit', 'permission_type_id' => 14]);
        Permission::Create(['name' => 'desempleo estado pago view', 'permission_type_id' => 14]);
        Permission::Create(['name' => 'desempleo estado pago annular', 'permission_type_id' => 14]);
        Permission::Create(['name' => 'desempleo estado pago delete', 'permission_type_id' => 14]);
        Permission::Create(['name' => 'desempleo estado pago export', 'permission_type_id' => 14]);

        Permission::Create(['name' => 'desempleo aviso print', 'permission_type_id' => 14]);
        Permission::Create(['name' => 'desempleo aviso edit', 'permission_type_id' => 14]);
        
        Permission::Create(['name' => 'desempleo historico pago view', 'permission_type_id' => 14]);
        Permission::Create(['name' => 'desempleo historico pago export', 'permission_type_id' => 14]);
  


        $role = Role::findOrFail(1);
        $role->givePermissionTo(Permission::all());

        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole(1);
        }
    }
}
