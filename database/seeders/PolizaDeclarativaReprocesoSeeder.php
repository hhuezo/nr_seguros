<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolizaDeclarativaReprocesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('poliza_declarativa_reproceso')->insert([
            ['Nombre' => 'F1 ERROR AL CONSIGNAR NUMERO GESTION', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'F1 ERROR DOCUMENTOS COLOCADOS CIA', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'F2 ERROR ENTREGA DE DOCUMENTOS', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'F2 ERROR DE PRIMA CAUSADA CIA', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'F2 ERROR A/C EMITIDO', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'F2 ERROR EN DATOS DEL CLIENTE', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'F2 ERROR EN TEXTOS DE LA COMUNICACION', 'Activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
