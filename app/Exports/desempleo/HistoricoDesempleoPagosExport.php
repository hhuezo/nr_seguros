<?php

namespace App\Exports\desempleo;

use App\Models\polizas\DesempleoCartera;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\VidaCartera;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpParser\Node\Expr\AssignOp\Concat;

class HistoricoDesempleoPagosExport implements FromCollection, WithHeadings
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }




    public function collection()
    {

        $data = DesempleoCartera::where('Axo', $this->request->Axo)
            ->where('Mes', $this->request->Mes)
            ->where('FechaInicio', $this->request->FechaInicio)
            ->where('FechaFinal', $this->request->FechaFinal)
            ->select([
                'Nit',
                'Dui',
                'Pasaporte',
                'Nacionalidad',
                'FechaNacimiento',
                'TipoPersona',
                'PrimerApellido',
                'SegundoApellido',
                'ApellidoCasada',
                'PrimerNombre',
                'SegundoNombre',
                'MontoOtorgado',
                'Sexo',
                'FechaOtorgamiento',
                'FechaVencimiento',
                // 'Ocupacion',
                DB::raw("CONCAT(NumeroReferencia, ' ') AS NumeroReferencia"),
                DB::raw("IF(MontoOtorgado IS NULL, '', ROUND(MontoOtorgado, 2)) AS MontoOtorgado"),
                // '' // Porcentaje Extraprima cambiar
            ])
            //->take(10)
            ->get();

        return $data;
    }


    public function headings(): array
    {
        return [
            'NIT',
            'DUI',
            'PASAPORTE O CARNET DE RESIDENTE ASEGURADO',
            'SALVADOREÑO',
            'FECHA NACIMIENTO',
            'TIPO DE PERSONA',
            'PRIMER APELLIDO',
            'SEGUNDO APELLIDO',
            'APELLIDO CASADA',
            'PRIMER NOMBRE',
            'SEGUNDO NOMBRE',
            'MONTO OTORGADO',
            'SEXO',
            'FECHA DE OTORGAMIENTO',
            'FECHA DE VENCIMIENTO',
            // 'OCUPACION',
            'No DE REFERENCIA DEL CRÉDITO',
            'MONTO OTORGADO'
        ];
    }
}
