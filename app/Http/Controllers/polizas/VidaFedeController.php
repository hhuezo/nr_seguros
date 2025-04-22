<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\VidaCarteraTempFedeImport;
use App\Models\polizas\Vida;
use App\Models\polizas\VidaTipoCartera;
use App\Models\temp\VidaCarteraTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VidaFedeController extends Controller
{
    //
    public function create_pago(Request $request)
    {
        $request->validate([
            'Axo' => 'required|integer',
            'Mes' => 'required|integer|between:1,12',
            'FechaInicio' => 'required|date',
            'FechaFinal' => 'required|date|after_or_equal:FechaInicio',
            'Archivo' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'PolizaVidaTipoCartera' => 'required|integer',
        ], [
            'Axo.required' => 'El campo Año es obligatorio.',
            'Axo.integer' => 'El campo Año debe ser un número entero.',
            'Axo.min' => 'El campo Año debe ser mayor o igual a 2022.',
            'Axo.max' => 'El campo Año no puede ser mayor al año actual.',
            'Mes.required' => 'El campo Mes es obligatorio.',
            'Mes.integer' => 'El campo Mes debe ser un número entero.',
            'Mes.between' => 'El campo Mes debe estar entre 1 y 12.',
            'FechaInicio.required' => 'El campo Fecha de inicio es obligatorio.',
            'FechaInicio.date' => 'El campo Fecha de inicio debe ser una fecha válida.',
            'FechaFinal.required' => 'El campo Fecha final es obligatorio.',
            'FechaFinal.date' => 'El campo Fecha final debe ser una fecha válida.',
            'FechaFinal.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
            'Archivo.required' => 'El campo Archivo es obligatorio.',
            'Archivo.file' => 'El campo Archivo debe ser un archivo válido.',
            'Archivo.mimes' => 'El archivo debe ser de tipo CSV, XLSX o XLS.',
            'Archivo.max' => 'El archivo no debe superar los 2MB.',
            'PolizaVidaTipoCartera.required' => 'El campo tipo cartera es obligatorio.',
            'PolizaVidaTipoCartera.integer' => 'El campo tipo cartera no válido.',
        ]);

        $id = $request->Id;

        $poliza_vida = Vida::findOrFail($id);

        $archivo = $request->Archivo;

        $excel = IOFactory::load($archivo);

        // Verifica si hay al menos dos hojas
        $sheetsCount = $excel->getSheetCount();

        if ($sheetsCount > 1) {
            return redirect('polizas/vida/' . $id . '?tab=2')
                ->withErrors(['excel_file' => 'La cartera solo puede contener un solo libro de Excel']);
        }

        //borrar datos de tabla temporal
        VidaCarteraTemp::where('User', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->delete();

        Excel::import(new VidaCarteraTempFedeImport($request->Axo, $request->Mes, $id, $request->FechaInicio, $request->FechaFinal, $request->PolizaVidaTipoCartera), $archivo);




        //verificando creditos repetidos
        /*$repetidos = VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)
            ->groupBy('NumeroReferencia')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $numerosRepetidos = $repetidos->isNotEmpty() ? $repetidos->pluck('NumeroReferencia') : null;

        if ($numerosRepetidos) {
            VidaCarteraTemp::where('User', auth()->user()->id)
                ->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)
                ->delete();

            $numerosStr = $numerosRepetidos->implode(', ');

            return back()
                ->withErrors(['Archivo' => "Existen números de crédito repetidos: $numerosStr"]);
        }*/



        //calculando errores de cartera
        $cartera_temp = VidaCarteraTemp::where('User', '=', auth()->user()->id)->where('PolizaVida', $id)->where('PolizaVidaTipoCartera', $request->PolizaVidaTipoCartera)->get();


        foreach ($cartera_temp as $obj) {
            $errores_array = [];

            if ($obj->FechaNacimientoDate == null) {
                $obj->TipoError = 1;
                $obj->update();
                array_push($errores_array, 1);
            }


            // 4 nombre o apellido
            if (trim($obj->PrimerApellido) == "" || trim($obj->PrimerNombre) == "") {
                $obj->TipoError = 6;
                $obj->update();

                array_push($errores_array, 6);
            }


            // 7 referencia si va vacia.
            if (trim($obj->NumeroReferencia) == "") {
                $obj->TipoError = 7;
                $obj->update();

                array_push($errores_array, 7);
            }


            // 10 error sexo
            if (empty(trim($obj->Sexo)) || !in_array($obj->Sexo, ['M', 'F'])) {
                $obj->TipoError = 8;
                $obj->update();
                $errores_array[] = 8; // Agregar error al array
            }

            $obj->Errores = $errores_array;
        }

        $data_error = $cartera_temp->where('TipoError', '<>', 0);

        if ($data_error->count() > 0) {
            return view('polizas.vida.respuesta_poliza_error', compact('data_error', 'poliza_vida'));
        }


        $temp_data_fisrt = VidaCarteraTemp::where('PolizaVida', $id)->where('User', auth()->user()->id)->where('PolizaVidaTipoCartera', '=', $request->PolizaVidaTipoCartera)->first();

        if (!$temp_data_fisrt) {
            alert()->error('No se han cargado las carteras');
            return back();
        }



        //calculando edades y fechas de nacimiento
        VidaCarteraTemp::where('User', auth()->user()->id)
            ->where('PolizaVida', $poliza_vida->Id)
            ->update([
                'Edad' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaFinal)"),
                'EdadDesembloso' => DB::raw("TIMESTAMPDIFF(YEAR, FechaNacimientoDate, FechaOtorgamientoDate)"),
            ]);



        //tasa diferenciada
        $vida_tipo_cartera = VidaTipoCartera::findOrFail($request->PolizaVidaTipoCartera);

        $tasas_diferenciadas = $vida_tipo_cartera->tasa_diferenciada;

        if ($vida_tipo_cartera->TipoCalculo == 1) {

            foreach ($tasas_diferenciadas as $tasa) {
                //dd($tasa);
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('FechaOtorgamientoDate', [$tasa->FechaDesde, $tasa->FechaHasta])
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else  if ($vida_tipo_cartera->TipoCalculo == 2) {

            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->whereBetween('SumaAsegurada', [$tasa->MontoDesde, $tasa->MontoHasta])
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $tasa->Tasa
                    ]);
            }
        } else {
            foreach ($tasas_diferenciadas as $tasa) {
                VidaCarteraTemp::where('User', auth()->user()->id)
                    ->where('PolizaVidaTipoCartera', $vida_tipo_cartera->Id)
                    ->update([
                        'MontoMaximoIndividual' => $vida_tipo_cartera->MontoMaximoIndividual,
                        'Tasa' => $poliza_vida->Tasa
                    ]);
            }
        }

        //agregar la validacion para las edades maximas de inscripcion

        alert()->success('Exito', 'La cartera fue subida con exito');


        return back();
    }
    
}
