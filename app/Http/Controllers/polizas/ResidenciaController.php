<?php

namespace App\Http\Controllers\polizas;

use App\Http\Controllers\Controller;
use App\Imports\PolizaResidenciaTempCarteraImport;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Bombero;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ConfiguracionRecibo;
use App\Models\catalogo\DatosGenerales;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use App\Models\catalogo\Ruta;
use App\Models\catalogo\TipoContribuyente;
use App\Models\catalogo\UbicacionCobro;
use App\Models\polizas\Comentario;
use App\Models\polizas\DetalleResidencia;
use App\Models\polizas\PolizaResidenciaCartera;
use App\Models\polizas\Residencia;
use App\Models\polizas\ResidenciaHistorialRecibo;
use App\Models\temp\PolizaResidenciaTempCartera;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Throwable;

class ResidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;

        $today = Carbon::now()->toDateString();

        session([
            'MontoCartera' => 0,
            'FechaInicio' => $today,
            'FechaFinal' => $today,
            'ExcelURL' => '',
            'tab' => 1
        ]);

        $residencias = Residencia::where('Activo', 1)->orderBy('Id', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $residencias->search(function ($r) use ($idRegistro) {
                return $r->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('polizas.residencia.index', compact('residencias', 'posicion'));
    }



    public function create()
    {
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->where('Nombre', 'like', '%fede%')->orWhere('Nombre', 'like', '%seguros e inversiones%')->get();
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }
        $ultimoRegistro = Residencia::where('Activo', 1)->orderByDesc('Id')->first();
        if (!$ultimoRegistro) {
            $ultimo = 1;
        } else {
            $ultimo =  $ultimoRegistro->Id + 1;
        }
        $cliente = Cliente::where('Activo', 1)->get();
        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();
        return view('polizas.residencia.create', compact(
            'ejecutivo',
            'productos',
            'planes',
            'cliente',
            'aseguradoras',
            'estados_poliza',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos',
            'ultimo'
        ));
    }


    protected function limpiarNumero($valor)
    {
        if (is_null($valor)) return null;

        // Eliminar espacios
        $valor = trim($valor);

        // Detectar cuál es el separador decimal: si la coma está después del punto, se considera decimal
        if (strpos($valor, ',') !== false && strpos($valor, '.') !== false) {
            if (strrpos($valor, ',') > strrpos($valor, '.')) {
                // Coma decimal, punto miles
                $valor = str_replace('.', '', $valor); // quitar puntos (miles)
                $valor = str_replace(',', '.', $valor); // cambiar coma por punto decimal
            } else {
                // Punto decimal, coma miles
                $valor = str_replace(',', '', $valor); // quitar comas (miles)
            }
        } else {
            // Solo comas: asumir coma decimal
            if (strpos($valor, ',') !== false) {
                $valor = str_replace(',', '.', $valor);
            }
            // Solo puntos: asumimos que está correcto (punto decimal)
            // si no tiene ninguno, queda igual
        }

        // Finalmente, eliminar todo carácter que no sea número, punto o signo menos (por si acaso)
        $valor = preg_replace('/[^0-9.\-]/', '', $valor);

        return $valor;
    }

    public function store(Request $request)
    {


        $request->merge([
            'LimiteGrupo'         => $this->limpiarNumero($request->input('LimiteGrupo')),
            'LimiteIndividual'    => $this->limpiarNumero($request->input('LimiteIndividual')),
            'Tasa'                => $this->limpiarNumero($request->input('Tasa')),
            'TasaDescuento'       => $this->limpiarNumero($request->input('TasaDescuento')),
            'TasaComision'        => $this->limpiarNumero($request->input('TasaComision')),
        ]);



        $request->validate([
            'NumeroPoliza'     => 'required|unique:poliza_residencia,NumeroPoliza',
            'Asegurado'        => 'required|exists:cliente,id',
            'Nit'              => 'nullable|string',
            'Aseguradora'      => 'required|exists:aseguradora,id',
            'Productos'        => 'required|exists:producto,id',
            'Planes'           => 'required|exists:plan,id',
            'VigenciaDesde'    => 'required|date',
            'VigenciaHasta'    => 'required|date|after:VigenciaDesde',
            'EstadoPoliza'     => 'required|exists:estado_poliza,id',
            'Ejecutivo'        => 'required|exists:ejecutivo,id',

            'TasaDescuento'    => 'required|numeric|min:0',
            'LimiteGrupo'      => 'required|numeric|min:0',
            'LimiteIndividual' => 'required|numeric|min:0',
            'Tasa'             => 'required|numeric|min:0',
            'TasaComision'     => 'required|numeric|min:0',

            'tipoTasa'         => 'required|in:0,1',
            //'ComisionIva'      => 'nullable|boolean',
        ], [
            'NumeroPoliza.required'     => 'El número de póliza es obligatorio.',
            'NumeroPoliza.unique'       => 'Ya existe una póliza con ese número.',

            'Asegurado.required'        => 'Debe seleccionar un asegurado.',
            'Asegurado.exists'          => 'El asegurado seleccionado no es válido.',

            'Aseguradora.required'      => 'Debe seleccionar una aseguradora.',
            'Aseguradora.exists'        => 'La aseguradora seleccionada no es válida.',

            'Productos.required'        => 'Debe seleccionar un producto.',
            'Productos.exists'          => 'El producto seleccionado no es válido.',

            'Planes.required'           => 'Debe seleccionar un plan.',
            'Planes.exists'             => 'El plan seleccionado no es válido.',

            'VigenciaDesde.required'    => 'Debe indicar la fecha de inicio de vigencia.',
            'VigenciaDesde.date'        => 'La fecha de inicio no es válida.',

            'VigenciaHasta.required'    => 'Debe indicar la fecha de fin de vigencia.',
            'VigenciaHasta.date'        => 'La fecha de fin no es válida.',
            'VigenciaHasta.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',

            'EstadoPoliza.required'     => 'Debe seleccionar un estado para la póliza.',
            'EstadoPoliza.exists'       => 'El estado de póliza seleccionado no es válido.',

            'Ejecutivo.required'        => 'Debe seleccionar un ejecutivo.',
            'Ejecutivo.exists'          => 'El ejecutivo seleccionado no es válido.',

            'TasaDescuento.required'    => 'Debe ingresar el porcentaje de descuento.',
            'TasaDescuento.numeric'     => 'El porcentaje de descuento debe ser numérico.',
            'TasaDescuento.min'         => 'El porcentaje de descuento no puede ser negativo.',

            'LimiteGrupo.required'      => 'Debe ingresar el límite de grupo.',
            'LimiteGrupo.numeric'       => 'El límite de grupo  tiene un formato no válido.',
            'LimiteGrupo.min'           => 'El límite de grupo no puede ser negativo.',

            'LimiteIndividual.required' => 'Debe ingresar el límite individual.',
            'LimiteIndividual.numeric'  => 'El límite individual tiene un formato no válido.',
            'LimiteIndividual.min'      => 'El límite individual no puede ser negativo.',

            'Tasa.required'             => 'Debe ingresar la tasa.',
            'Tasa.numeric'              => 'La tasa debe ser numérica.',
            'Tasa.min'                  => 'La tasa no puede ser negativa.',

            'TasaComision.required'     => 'Debe ingresar el porcentaje de comisión.',
            'TasaComision.numeric'      => 'El porcentaje de comisión tiene un formato no válido.',
            'TasaComision.min'          => 'El porcentaje de comisión no puede ser negativo.',

            'tipoTasa.required'         => 'Debe seleccionar el tipo de tasa.',
            'tipoTasa.in'               => 'El tipo de tasa seleccionado no es válido.',
        ]);

        try {

            $residencia = new Residencia();
            $residencia->Numero = 1;
            $residencia->NumeroPoliza = $request->NumeroPoliza;
            $residencia->Codigo = $request->Codigo;
            $residencia->Aseguradora = $request->Aseguradora;
            $residencia->Asegurado = $request->Asegurado;
            $residencia->EstadoPoliza = $request->EstadoPoliza;
            $residencia->VigenciaDesde = $request->VigenciaDesde;
            $residencia->VigenciaHasta = $request->VigenciaHasta;
            $residencia->LimiteGrupo = $request->LimiteGrupo;
            $residencia->LimiteIndividual = $request->LimiteIndividual;
            $residencia->Tasa = $request->Tasa;
            $residencia->Ejecutivo = $request->Ejecutivo;
            $residencia->TasaDescuento = $request->TasaDescuento;
            $residencia->Nit = $request->Nit;
            $residencia->Activo = 1;
            if ($request->DescuentoIva == 'on') {
                $residencia->DescuentoIva = 1;
            } else {
                $residencia->DescuentoIva = 0;
            }
            $residencia->Mensual = $request->tipoTasa;
            $residencia->Plan = $request->Planes;
            $residencia->Comision = $request->TasaComision;
            if ($request->ComisionIva == 'on') {
                $residencia->DescuentoIva = 1;
            } else {
                $residencia->DescuentoIva = 0;
            }
            $residencia->save();


            return redirect('polizas/residencia/' . $residencia->Id . '/edit?tab=2')->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar la póliza: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function cancelar_pago(Request $request)
    {
        //  dd($request->Residencia);
        //   dd($request->MesCancelar);
        try {
            $poliza_temp = PolizaResidenciaTempCartera::where('PolizaResidencia', '=', $request->Residencia)->where('User', '=', auth()->user()->id)->first();
            $poliza = PolizaResidenciaCartera::where('PolizaResidencia', '=', $request->Residencia)->where('Mes', '=', $poliza_temp->Mes)
                ->where('Axo', '=', $poliza_temp->Axo)->where('User', '=', auth()->user()->id)
                ->delete();

            PolizaResidenciaTempCartera::where('PolizaResidencia', '=', $request->Residencia)->delete();
            // dd($poliza);
        } catch (\Throwable $th) {
            //throw $th;
        }
        session(['MontoCartera' => 0]);
        session(['ExcelURL' => null]);

        alert()->success('El cobro se ha eliminado correctamente');
        return redirect('polizas/residencia/' . $request->Residencia . '/edit?tab=2');
    }


    public function reiniciar_carga(Request $request)
    {
        $anio = $request->Axo;
        $mes = $request->Mes;
        $residencia = $request->PolizaResidencia;

        DB::beginTransaction();

        try {
            // 1️⃣ Eliminar los registros actuales en poliza_residencia_temp_cartera
            DB::table('poliza_residencia_temp_cartera')
                ->where('Axo', $anio)
                ->where('Mes', $mes)
                ->where('PolizaResidencia', $residencia)
                ->delete();

            // 2️⃣ Insertar los registros del historial nuevamente en la tabla temporal
            DB::statement("
            INSERT INTO poliza_residencia_temp_cartera (
                Dui,
                Nit,
                Pasaporte,
                CarnetResidencia,
                Nacionalidad,
                FechaNacimiento,
                TipoPersona,
                NombreCompleto,
                NombreSociedad,
                Genero,
                Direccion,
                FechaOtorgamiento,
                FechaVencimiento,
                NumeroReferencia,
                SumaAsegurada,
                Tarifa,
                PrimaMensual,
                NumeroCuotas,
                TipoDeuda,
                ClaseCartera,
                User,
                Axo,
                Mes,
                PolizaResidencia,
                FechaInicio,
                FechaFinal,
                Errores
            )
            SELECT
                Dui,
                Nit,
                Pasaporte,
                CarnetResidencia,
                Nacionalidad,
                FechaNacimiento,
                TipoPersona,
                NombreCompleto,
                NombreSociedad,
                Genero,
                Direccion,
                FechaOtorgamiento,
                FechaVencimiento,
                NumeroReferencia,
                SumaAsegurada,
                Tarifa,
                PrimaMensual,
                NumeroCuotas,
                TipoDeuda,
                ClaseCartera,
                User,
                Axo,
                Mes,
                PolizaResidencia,
                FechaInicio,
                FechaFinal,
                Errores
            FROM poliza_residencia_temp_cartera_historial
            WHERE Axo = ? AND Mes = ? AND PolizaResidencia = ?
        ", [$anio, $mes, $residencia]);

            // 3️⃣ Eliminar los registros del historial una vez restaurados
            DB::table('poliza_residencia_temp_cartera_historial')
                ->where('Axo', $anio)
                ->where('Mes', $mes)
                ->where('PolizaResidencia', $residencia)
                ->delete();

            // 4️⃣ Eliminar los registros de la cartera real (que no tengan detalle)
            DB::table('poliza_residencia_cartera')
                ->where('Axo', $anio)
                ->where('Mes', $mes)
                ->where('PolizaResidencia', $residencia)
                //->whereNull('PolizaResidenciaDetalle')
                ->delete();

            DB::commit();

            alert()->success('La carga de póliza de residencia ha sido reiniciada correctamente');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al reiniciar carga de residencia: ' . $e->getMessage());
            alert()->error('Hubo un error al reiniciar la carga de residencia');
            return back();
        }
    }


    public function agregar_comentario(Request $request)
    {
        $time = Carbon::now('America/El_Salvador');
        $comen = new Comentario();
        $comen->Comentario = $request->Comentario;
        $comen->Activo = 1;
        if ($request->TipoComentario == '') {
            $comen->DetalleResidencia = '';
        } else {
            $comen->DetalleResidencia == $request->TipoComentario;
        }
        $comen->Usuario = auth()->user()->id;
        $comen->FechaIngreso = $time;
        $comen->Residencia = $request->ResidenciaComment;
        $comen->save();
        alert()->success('El registro del comentario ha sido creado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia/' . $request->ResidenciaComment . '/edit');
    }

    public function eliminar_comentario(Request $request)
    {

        $comen = Comentario::findOrFail($request->IdComment);
        $comen->Activo = 0;
        $comen->update();
        alert()->success('El registro del comentario ha sido elimando correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia/' . $comen->Residencia . '/edit');
    }

    public function edit(Request $request, $id)
    {
        $tab = $request->tab ?? 1;

        $residencia = Residencia::findOrFail($id);


        $aseguradoras = Aseguradora::where('Activo', 1)->get();  //where('Nombre', 'like', '%fede%')->orWhere('Nombre', 'like', '%sisa%')->
        $estados_poliza = EstadoPoliza::where('Activo', '=', 1)->get();
        $cliente = Cliente::where('Activo', 1)->get();

        $tipos_contribuyente = TipoContribuyente::get();
        $rutas = Ruta::where('Activo', '=', 1)->get();
        $ubicaciones_cobro = UbicacionCobro::where('Activo', '=', 1)->get();
        $detalle = DetalleResidencia::where('Residencia', $residencia->Id)->orderBy('Id', 'desc')->get();
        $ultimo_pago = DetalleResidencia::where('Residencia', $id)->orderBy('Id', 'desc')->first();
        $productos = Producto::where('Activo', 1)->get();
        $planes = Plan::where('Activo', 1)->get();

        $comentarios = Comentario::where('Residencia', '=', $id)->where('Activo', 1)->get();
        $fechas = PolizaResidenciaTempCartera::where('PolizaResidencia', $id)->first();

        $now = Carbon::now();
        $mes = $now->month;
        $anio = $now->year;
        // Primer día del mes
        // Primer día del mes
        $FechaInicio = $now->copy()->startOfMonth()->format('Y-m-d');

        // Último día del mes
        $FechaFinal = $now->copy()->endOfMonth()->format('Y-m-d');

        if ($ultimo_pago) {
            // Si hay pago, tomar la fecha inicial y final con +1 mes exacto
            $fecha_inicial = Carbon::parse($ultimo_pago->FechaFinal);
            $fecha_final = $fecha_inicial->copy()->addMonth();

            $ulrtimoAxo = $ultimo_pago->Axo;
            $UltimoMes = (int) $ultimo_pago->Mes;

            // Crear una fecha base con día 1
            $fecha = Carbon::create($ulrtimoAxo, $UltimoMes, 1);

            // Aumentar un mes
            $fecha->addMonth();

            // Obtener los nuevos valores
            $axo = $fecha->year;
            $mes = $fecha->month;

            // Formato final Y-m-d
            $FechaInicio = $fecha_inicial->format('Y-m-d');
            $FechaFinal = $fecha_final->format('Y-m-d');
        }



        if (strpos($residencia->aseguradoras->Nombre, 'FEDE') === false) {
            if ($residencia->Mensual == 1) {
                $valorTasa = round($residencia->Tasa / 1000 / 12, 8);
            } else {
                $valorTasa = round($residencia->Tasa / 1000 / 12, 8);
            }
        } else {
            if ($residencia->Mensual == 1) {   //modificar al confirmar
                $valorTasa = round($residencia->Tasa / 1000, 8);
            } else {
                $valorTasa = round($residencia->Tasa / 1000, 8);
            }
        }


        $ejecutivo = Ejecutivo::where('Activo', 1)->get();
        $bombero = Bombero::where('Activo', 1)->first();
        if ($bombero) {
            $bomberos = $bombero->Valor;
        } else {
            $bomberos = 0;
        }

        $fecha = PolizaResidenciaCartera::select('Mes', 'Axo', 'FechaInicio', 'FechaFinal')
            ->where('PolizaResidencia', '=', $id)
            // ->where(function ($query) {
            //     $query->where('PolizaResidenciaDetalle', '=', 0)
            //         ->orWhere('PolizaResidenciaDetalle', '=', null);
            // })
            ->orderByDesc('Id')->first();

        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        // session(['MontoCartera' => 0]);

        return view('polizas.residencia.edit', compact(
            'fecha',
            'fechas',
            'FechaInicio',
            'FechaFinal',
            'mes',
            'axo',
            'residencia',
            'ejecutivo',
            'detalle',
            'cliente',
            'valorTasa',
            'aseguradoras',
            'planes',
            'estados_poliza',
            'tipos_contribuyente',
            'rutas',
            'ubicaciones_cobro',
            'bomberos',
            'meses',
            'ultimo_pago',
            'comentarios',
            'productos',
            'tab'
        ));
    }


    public function update(Request $request, $id)
    {
        $messages = [


            'LimiteGrupo.required' => 'El Límite Grupal es requerido',
            'LimiteIndividual.required' => 'El Límite Individual es requerido',
            'Tasa.required' => 'El valor de la Tasa es requerido',
            'Comision.required' => 'El valor de la Tas de Comisión es requerido',

        ];

        $request->validate([
            'LimiteGrupo' => 'required',
            'LimiteIndividual' => 'required',
            'Tasa' => 'required',
            'Comision' => 'required'

        ], $messages);

        $residencia = Residencia::findOrFail($id);

        $residencia->NumeroPoliza = $request->NumeroPoliza;
        $residencia->Aseguradora = $request->Aseguradora;
        $residencia->Asegurado = $request->Asegurado;
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        $residencia->Tasa = $request->Tasa;
        $residencia->Ejecutivo = $request->Ejecutivo;
        $residencia->TasaDescuento = $request->TasaDescuento;
        $residencia->Nit = $request->Nit;
        if ($request->DescuentoIva == 'on') {
            $residencia->DescuentoIva = 1;
        } else {
            $residencia->DescuentoIva = 0;
        }
        $residencia->Mensual = $request->tipoTasa;
        $residencia->Plan = $request->Plan;
        $residencia->Comision = $request->Comision;
        if ($request->ComisionIva == 'on') {
            $residencia->DescuentoIva = 1;
        } else {
            $residencia->DescuentoIva = 0;
        }

        $residencia->Modificar = 0;
        $residencia->update();


        session(['tab' => 1]);
        return back();
    }

    public function active_edit($id)
    {
        $residencia = Residencia::findOrfail($id);
        $residencia->Modificar = 1;
        $residencia->update();
        alert()->success('El activado la modificacion correctamente');
        return back();
    }
    public function desactive_edit($id)
    {
        $residencia = Residencia::findOrfail($id);
        $residencia->Modificar = 0;
        $residencia->update();
        alert()->success('El activado la modificacion correctamente');
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $residencia = Residencia::findOrFail($id);
        $residencia->Activo = 0;
        $residencia->update();
        alert()->success('El registro ha sido creado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return Redirect::to('polizas/residencia');
    }

    public function create_pago(Request $request)
    {
        $fecha = Carbon::create(null, $request->Mes, 1);
        $nombreMes = $fecha->locale('es')->monthName;
        $idUnicoCartera = Str::random(40);
        $time = Carbon::now('America/El_Salvador');

        $residencia = Residencia::findOrFail($request->Id);

        if ($request->Mes == 1) {
            $mes_evaluar = 12;
            $axo_evaluar = $request->Axo - 1;
        } else {
            $mes_evaluar = $request->Mes - 1;
            $axo_evaluar = $request->Axo;
        }

        try {
            $archivo = $request->Archivo;
            PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)->delete();
            //PolizaResidenciaTempCartera::truncate();
            //dd(Excel::toArray(new PolizaResidenciaTempCarteraImport($request->Axo, $request->Mes, $residencia->Id, $request->FechaInicio, $request->FechaFinal), $archivo));

            $spreadsheet = IOFactory::load($archivo);
            $worksheet = $spreadsheet->getActiveSheet();
            // $worksheet->getMergeCells() Se verifica si existen celdas combinadas
            if (count($worksheet->getMergeCells())) {

                alert()->error('El Documento NO puede tener celdas combinadas, por favor separe las siguientes celdas: ' . implode(', ', $worksheet->getMergeCells()))->showConfirmButton('Aceptar', '#3085d6');
                return back();
            }

            Excel::import(new PolizaResidenciaTempCarteraImport($request->Axo, $request->Mes, $residencia->Id, $request->FechaInicio, $request->FechaFinal), $archivo);

            $monto_cartera_total = PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)->sum('SumaAsegurada');

            $asegurados_limite_individual = PolizaResidenciaTempCartera::where('User', '=', auth()->user()->id)
                ->where('SumaAsegurada', '>', $residencia->LimiteIndividual)
                ->get();

            if ($monto_cartera_total > $residencia->LimiteGrupo) {
                alert()->error('Error, el saldo supera el limite de grupo.<br> Limite de grupo: $' . number_format($residencia->LimiteGrupo, 2, '.', ',') . '<br>Saldo total de la cartera: $' . number_format($monto_cartera_total, 2, '.', ','))->showConfirmButton('Aceptar', '#3085d6');
                return back();
            }

            if ($asegurados_limite_individual->count() > 0) {
                alert()->error('Error, Hay polizas que superan el limte individual')->showConfirmButton('Aceptar', '#3085d6');
                $idPolizaResidencia = $residencia->Id;
                return view('polizas.validacion_cartera.resultado', compact('asegurados_limite_individual', 'idPolizaResidencia'));
            }

            /* if ($request->Validar == "on") {

                $eliminados = DB::select('CALL lista_residencia_eliminados(?, ?, ?, ?, ?, ?)', [$axo_evaluar, $mes_evaluar, $residencia->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                $nuevos = DB::select('CALL lista_residencia_nuevos(?, ?, ?, ?, ?, ?)', [$axo_evaluar, $mes_evaluar, $residencia->Id, auth()->user()->id, $request->Axo, $request->Mes]);

                return view('polizas.validacion_cartera.resultado', compact('nuevos', 'eliminados'));
            }*/

            //   DB::statement("CALL insertar_temp_cartera_residencia(?, ?, ?, ?, ?)", [auth()->user()->id, $request->Axo, $request->Mes, $residencia->Id, $idUnicoCartera]);

            $this->insertar_temp(auth()->user()->id, $request->Axo, $request->Mes, $residencia->Id, $idUnicoCartera);

            $monto_cartera_total = PolizaResidenciaCartera::where('Axo', $request->Axo)
                ->where('Mes', $request->Mes)
                ->where('PolizaResidencia', $residencia->Id)
                ->where('User', auth()->user()->id)
                ->where('IdUnicoCartera', $idUnicoCartera)->sum('SumaAsegurada');

            session(['idUnicoCartera' => $idUnicoCartera]);
            session(['MontoCartera' => $monto_cartera_total]);
            session(['FechaInicio' => $request->FechaInicio]);
            session(['FechaFinal' => $request->FechaFinal]);
            $idA = uniqid();
            $filePath = 'documentos/polizas/' . $idA . $residencia->NumeroPoliza . '-' . $nombreMes . '-' . $request->Axo . '-Residencia.xlsx';

            $archivo->move(public_path("documentos/polizas/"), $filePath);
            // Storage::disk('public')->put($filePath, file_get_contents($archivo));

            session(['ExcelURL' => $filePath]);

            alert()->success('El registro ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');

            return redirect('polizas/residencia/' . $request->Id . '/edit?tab=2');
        } catch (Throwable $e) {
            print($e);
            return false;
        }
    }

    public function agregar_pago(Request $request)
    {

        //dd($request->MontoCartera);
        $residencia = Residencia::findOrFail($request->Residencia);
        $time = Carbon::now('America/El_Salvador');

        $recibo = DatosGenerales::orderByDesc('Id_recibo')->first();
        if (!$request->ExcelURL) {
            alert()->error('No se puede generar el pago, falta subir cartera')->showConfirmButton('Aceptar', '#3085d6');
        } else {

            $usuariosReportados = DB::table('poliza_residencia_cartera')
                ->where('PolizaResidencia', $request->Residencia)
                ->where('Axo', $request->Axo)
                ->where('Mes', $request->Mes)
                ->count();


            $detalle = new DetalleResidencia();
            $detalle->FechaInicio = $request->FechaInicio;
            $detalle->FechaFinal = $request->FechaFinal;
            $detalle->MontoCartera = $request->MontoCartera;
            $detalle->Residencia = $request->Residencia;
            $detalle->Tasa = $request->Tasa;
            $detalle->PrimaCalculada = $request->PrimaCalculada;
            $detalle->Descuento = $request->Descuento;
            $detalle->PrimaDescontada = $request->PrimaDescontada;
            $detalle->ImpuestoBomberos = $request->ImpuestoBomberos;
            $detalle->GastosEmision = $request->GastosEmision;
            $detalle->Otros = $request->Otros;
            $detalle->SubTotal = $request->SubTotal;
            $detalle->Iva = $request->Iva;
            $detalle->TasaComision = $request->TasaComision;
            $detalle->Comision = $request->Comision;
            $detalle->IvaSobreComision = $request->IvaSobreComision;
            $detalle->Retencion = $request->Retencion;
            $detalle->ValorCCF = $request->ValorCCF;
            $detalle->Comentario = $request->Comentario;
            $detalle->APagar = $request->APagar;
            $detalle->Axo = $request->Axo;
            $detalle->Mes = $request->Mes;

            $detalle->PrimaTotal = $request->PrimaTotal;
            $detalle->DescuentoIva = $request->DescuentoIva;
            $detalle->ExtraPrima = $request->ExtraPrima;
            $detalle->ExcelURL = $request->ExcelURL;
            $detalle->NumeroRecibo = ($recibo->Id_recibo) + 1;
            $detalle->Usuario = auth()->user()->id;
            $detalle->FechaIngreso = $time->format('Y-m-d');
            $detalle->save();

            $comen = new Comentario();
            $comen->Comentario = 'Se agrego el pago de la cartera';
            $comen->Activo = 1;
            $comen->Usuario = auth()->user()->id;
            $detalle->UsuariosReportados = $usuariosReportados;


            $comen->FechaIngreso = $time;
            $comen->Residencia = $request->Residencia;
            $comen->DetalleResidencia = $detalle->Id;
            $comen->save();

            PolizaResidenciaCartera::where('FechaInicio', $request->FechaInicio)->where('FechaFinal', $request->FechaFinal)->where('PolizaResidencia', $request->Residencia)
                ->update(['DetalleResidencia' => $detalle->Id]);


            $recibo->Id_recibo = ($recibo->Id_recibo) + 1;
            $recibo->update();
            session(['MontoCartera' => 0]);
            alert()->success('El registro de pago ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        }
        return back();
    }

    public function insertar_temp($usuarioTemp, $axoTemp, $mesTemp, $polizaResidenciaId, $idUnicoCartera)
    {
        return DB::table('poliza_residencia_cartera')->insertUsing(
            [
                'Dui',
                'Nit',
                'Pasaporte',
                'CarnetResidencia',
                'Nacionalidad',
                'FechaNacimiento',
                'TipoPersona',
                'NombreCompleto',
                'NombreSociedad',
                'Genero',
                'Direccion',
                'FechaOtorgamiento',
                'FechaVencimiento',
                'NumeroReferencia',
                'SumaAsegurada',
                'Tarifa',
                'PrimaMensual',
                'NumeroCuotas',
                'TipoDeuda',
                'ClaseCartera',
                'User',
                'Axo',
                'Mes',
                'PolizaResidencia',
                'FechaInicio',
                'FechaFinal',
                'IdUnicoCartera'
            ],
            DB::table('poliza_residencia_temp_cartera')
                ->select(
                    'Dui',
                    'Nit',
                    'Pasaporte',
                    'CarnetResidencia',
                    'Nacionalidad',
                    'FechaNacimiento',
                    'TipoPersona',
                    'NombreCompleto',
                    'NombreSociedad',
                    'Genero',
                    'Direccion',
                    'FechaOtorgamiento',
                    'FechaVencimiento',
                    'NumeroReferencia',
                    'SumaAsegurada',
                    'Tarifa',
                    'PrimaMensual',
                    'NumeroCuotas',
                    'TipoDeuda',
                    'ClaseCartera',
                    'User',
                    'Axo',
                    'Mes',
                    'PolizaResidencia',
                    'FechaInicio',
                    'FechaFinal',
                    DB::raw("'" . $idUnicoCartera . "' AS IdUnicoCartera")
                )
                ->where('User', $usuarioTemp)
                ->where('Axo', $axoTemp)
                ->where('Mes', $mesTemp)
                ->where('PolizaResidencia', $polizaResidenciaId)
        );
    }

    public function save_recibo($residencia, $detalle)
    {
        //dd($detalle);
        $recibo_historial = new ResidenciaHistorialRecibo();
        $recibo_historial->PolizaResidenciaDetalle = $detalle->Id;
        $recibo_historial->ImpresionRecibo = $detalle->ImpresionRecibo;
        $recibo_historial->NombreCliente = $residencia->clientes->Nombre;
        $recibo_historial->NitCliente = $residencia->clientes->Nit;
        $recibo_historial->DireccionResidencia = $residencia->clientes->DireccionResidencia ?? $residencia->clientes->DireccionCorrespondencia;
        $recibo_historial->Departamento = $residencia->clientes->distrito->municipio->departamento->Nombre;
        $recibo_historial->Municipio = $residencia->clientes->distrito->municipio->Nombre;
        $recibo_historial->NumeroRecibo = $detalle->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $residencia->aseguradoras->Nombre;
        $recibo_historial->ProductoSeguros = $residencia->planes->productos->Nombre;
        $recibo_historial->NumeroPoliza = $residencia->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $residencia->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $residencia->VigenciaHasta;
        $recibo_historial->FechaInicio = $detalle->FechaInicio;
        $recibo_historial->FechaFin = $detalle->FechaFinal;
        $recibo_historial->Anexo = $detalle->Anexo;
        $recibo_historial->Referencia = $detalle->Referencia;
        $recibo_historial->FacturaNombre = $residencia->clientes->Nombre;
        $recibo_historial->MontoCartera = $detalle->MontoCartera;
        $recibo_historial->PrimaCalculada = $detalle->PrimaCalculada;
        $recibo_historial->SubTotal = $detalle->SubTotal;
        $recibo_historial->Iva = $detalle->Iva;
        $recibo_historial->TotalFactura = $detalle->APagar;
        $recibo_historial->Descuento = $detalle->Descuento;
        $recibo_historial->PordentajeDescuento = $detalle->TasaDescuento;
        $recibo_historial->PrimaDescontada = $detalle->PrimaDescontada;
        $recibo_historial->TotalAPagar = $detalle->APagar;
        $recibo_historial->TasaComision = $detalle->TasaComision ?? '';
        $recibo_historial->Comision = $detalle->Comision;
        $recibo_historial->IvaSobreComision = $detalle->IvaSobreComision;
        $recibo_historial->SubTotalComision = $detalle->SubTotalComision ?? 0;
        $recibo_historial->Retencion =  $detalle->Retencion;
        $recibo_historial->ValorCCF = $detalle->ValorCCF;
        $recibo_historial->NumeroCorrelativo = $detalle->NumeroCorrelativo;
        $recibo_historial->Otros = $detalle->Otros;
        $recibo_historial->Cuota = '01/01';
        $recibo_historial->FechaVencimiento = $detalle->FechaInicio ?? '';
        $recibo_historial->PagoLiquidoPrima = $detalle->PagoLiquidoPrima ?? 0;
        $recibo_historial->CreatedAt = $detalle->CreatedAt;
        $recibo_historial->UpdatedAt = $detalle->UpdatedAt;
        $recibo_historial->Usuario = $detalle->Usuario;
        $recibo_historial->Activo = $detalle->Activo;
        $recibo_historial->save();
        return $recibo_historial;
    }

    public function edit_pago(Request $request)
    {
        session(['tab' => 4]);
        $detalle = DetalleResidencia::findOrFail($request->Id);
        $residencia = Residencia::findOrFail($detalle->Residencia);
        $time = Carbon::now('America/El_Salvador');

        if ($detalle->SaldoA == null && $detalle->ImpresionRecibo == null) {
            $detalle->SaldoA = $request->SaldoA;
            $detalle->ImpresionRecibo = $request->ImpresionRecibo;
            $detalle->Comentario = $request->Comentario;
            $detalle->update();
            $configuracion = ConfiguracionRecibo::first();

            $recibo_historial = ResidenciaHistorialRecibo::where('PolizaResidenciaDetalle', $request->Id)->orderBy('Id', 'desc')->first();
            if (!$recibo_historial) {
                $recibo_historial = $this->save_recibo($residencia, $detalle);
            }

            $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion', 'detalle', 'residencia'))->setWarnings(false)->setPaper('letter');
            return $pdf->stream('Recibo.pdf');

            return back();
        } else {

            //dd($request->EnvioCartera .' 00:00:00');
            if ($request->EnvioCartera) {
                $detalle->EnvioCartera = $request->EnvioCartera;
                $detalle->ComCartera = $request->Comentario;
            }
            if ($request->EnvioPago) {
                $detalle->EnvioPago = $request->EnvioPago;
                $detalle->ComPago = $request->Comentario;
            }
            if ($request->PagoAplicado) {
                $detalle->PagoAplicado = $request->PagoAplicado;
                $detalle->ComAplicado = $request->Comentario;
            }

            $comen = new Comentario();
            $comen->Comentario = $request->Comentario;
            $comen->Activo = 1;
            $comen->Usuario = auth()->user()->id;
            $comen->FechaIngreso = $time;
            $comen->Residencia = $detalle->Residencia;
            $comen->DetalleResidencia = $detalle->Id;
            $comen->save();


            /*$detalle->EnvioPago = $request->EnvioPago;
            $detalle->PagoAplicado = $request->PagoAplicado;*/
            $detalle->update();
            alert()->success('El registro ha sid:o ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        }



        return back();
    }

    public function recibo_pago($id, Request $request)
    {

        $detalle = DetalleResidencia::findOrFail($id);
        $residencia = Residencia::findOrFail($detalle->Residencia);
        $cliente = Cliente::findOrFail($residencia->Asegurado);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $detalle->SaldoA = $request->SaldoA;
        $detalle->ImpresionRecibo = $request->ImpresionRecibo;
        $detalle->Referencia = $request->Referencia;
        $detalle->Anexo = $request->Anexo;
        $detalle->NumeroCorrelativo = $request->NumeroCorrelativo;
        $detalle->update();
        // dd($detalle);
        //$calculo = $this->monto($residencia, $detalle);
        //llenar recibo
        $recibo_historial = $this->save_recibo($residencia, $detalle);
        ///dd($recibo_historial);
        $configuracion = ConfiguracionRecibo::first();
        $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion', 'detalle', 'residencia', 'meses', 'cliente', 'recibo_historial'))->setWarnings(false)->setPaper('letter');
        return $pdf->stream('Recibo.pdf');

        //  return back();
    }

    public function get_recibo($id)
    {
        $detalle = DetalleResidencia::findOrFail($id);
        $residencia = Residencia::findOrFail($detalle->Residencia);


        $cliente = Cliente::findOrFail($residencia->Asegurado);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $calculo = $this->monto($residencia, $detalle);
        // dd($calculo);
        $configuracion = ConfiguracionRecibo::first();

        $recibo_historial = ResidenciaHistorialRecibo::where('PolizaResidenciaDetalle', $id)->orderBy('Id', 'desc')->first();
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($residencia, $detalle);
        }

        //  dd($recibo_historial);


        //return view('polizas.residencia.recibo', compact('configuracion','cliente',  'detalle', 'residencia', 'meses', 'calculo'));
        $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion', 'cliente', 'detalle', 'residencia', 'meses', 'calculo', 'recibo_historial'))->setWarnings(false)->setPaper('letter');
        //  dd($detalle);
        return $pdf->stream('Recibos.pdf');
    }

    public function get_pago($id)
    {
        return DetalleResidencia::findOrFail($id);
    }

    public function monto($residencia, $detalle)
    {
        $calculo = array();
        $bomberos = Bombero::where('Activo', 1)->first();
        $monto = $detalle->MontoCartera;
        $desde = Carbon::parse($residencia->VigenciaDesde);
        $hasta = Carbon::parse($residencia->VigenciaHasta);
        $inicio = Carbon::parse($detalle->FechaInicio);
        $final = Carbon::parse($detalle->FechaFinal);
        $tasa = $residencia->Tasa;
        $dias_axo = $residencia->aseguradoras->Dias365 == 1 ? 365 : $desde->diffInDays($hasta);
        $dias_mes = $final->diffInDays($inicio);



        if (strpos($residencia->aseguradoras->Nombre, 'FEDE') === false) {
            //sisa
            if ($residencia->Mensual == 0) {
                $tasaFinal = ($tasa / 1000) / 12;
            } else {
                $tasaFinal = $tasa / 1000;
            }
        } else {
            //fede
            if ($residencia->Mensual == 0) {    //falta confirmacion de tasa anual
                $tasaFinal = ($tasa / 1000); // / 12;
            } else {
                $tasaFinal = $tasa / 1000;
            }
        }



        if ($residencia->aseguradoras->Diario == 1) {
            $prima_calculada = (($monto * $tasaFinal) / $dias_axo) * $dias_mes;
        } else {
            $prima_calculada = $monto * $tasaFinal;
        }

        array_push($calculo, $prima_calculada);  // prima calculada

        $prima_total = $prima_calculada + $detalle->ExtraPrima;
        $tasa_descuento = $residencia->TasaDescuento;
        if ($tasa_descuento < 0) {
            $descuento = $tasa_descuento * $prima_total;
        } else {
            $descuento = ($tasa_descuento / 100 * $prima_total);
        }

        array_push($calculo, $descuento); // descuento rentabilidad

        $prima_descontada = $prima_total - $descuento;

        array_push($calculo, $prima_descontada);   //prima descontada
        if ($bomberos) {
            $calculo_bomberos = $monto * ($bomberos->Valor / 100);
        } else {
            $calculo_bomberos = 0;
        }

        array_push($calculo, $calculo_bomberos); //calculo de bomberos

        $sub = $prima_descontada - $calculo_bomberos;

        array_push($calculo, $sub);   //calculo_subtotal

        $iva = $sub * 0.13;
        array_push($calculo, $iva);  //calculo iva

        $ccf = $prima_descontada * ($residencia->Comision / 100);
        array_push($calculo, $ccf);   //valor ccf

        $iva_ccf = $ccf * 0.13;
        array_push($calculo, $iva_ccf); // iva ccf

        $total_ccf = $ccf + $iva_ccf;
        array_push($calculo, $total_ccf);  //total ccf

        $a_pagar = $sub + $iva - $total_ccf;
        array_push($calculo, $a_pagar);   //calculo a pagar

        $facturar = $sub + $iva;
        array_push($calculo, $facturar);   //calculo a facturar

        return $calculo;
    }


    public function renovar($id)
    {
        $residencia = Residencia::findOrFail($id);
        $estados_poliza = EstadoPoliza::where('Activo', 1)->get();
        $ejecutivo = Ejecutivo::where('Activo', '=', 1)->get();
        return view('polizas.residencia.renovar', compact('residencia', 'estados_poliza', 'ejecutivo'));
    }

    public function renovarPoliza(Request $request, $id)
    {
        $residencia = Residencia::findOrFail($id);
        $residencia->Mensual = $request->Mensual; //valor de radio button
        $residencia->EstadoPoliza = $request->EstadoPoliza;
        $residencia->VigenciaDesde = $request->VigenciaDesde;
        $residencia->VigenciaHasta = $request->VigenciaHasta;
        $residencia->LimiteGrupo = $request->LimiteGrupo;
        $residencia->LimiteIndividual = $request->LimiteIndividual;
        // $residencia->MontoCartera = $request->MontoCartera;
        $residencia->Tasa = $request->Tasa;
        $residencia->Ejecutivo = $request->Ejecutivo;
        $residencia->update();

        alert()->success('La poliza fue renovada correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return back();
    }

    public function delete_pago($id)
    {
        $detalle = DetalleResidencia::findOrFail($id);
        $detalle->Activo = 0;
        $detalle->update();
        alert()->success('El registro ha sido ingresado correctamente')->showConfirmButton('Aceptar', '#3085d6');
        return back();
    }

    public function get_recibo_edit($id)
    {
        $detalle = DetalleResidencia::findOrFail($id);
        $residencia = Residencia::findOrFail($detalle->Residencia);
        $recibo_historial = ResidenciaHistorialRecibo::where('PolizaResidenciaDetalle', $id)->orderBy('id', 'desc')->first();
        if (!$recibo_historial) {
            $recibo_historial = $this->save_recibo($residencia, $detalle);
            //dd("insert");
        }
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $recibo_historial = ResidenciaHistorialRecibo::where('PolizaResidenciaDetalle', $id)->orderBy('id', 'desc')->first();
        if ($recibo_historial->DireccionResidencia == '' || $recibo_historial->DireccionResidencia == '(vacio)') {
            $recibo_historial->DireccionResidencia =
                $detalle->residencia?->clientes?->DireccionResidencia
                ?? $detalle->residencia?->clientes?->DireccionCorrespondencia
                ?? '';
        }

        if ($recibo_historial->ProductoSeguros == '') {
            $recibo_historial->ProductoSeguros =  $detalle->deuda?->planes->productos->Nombre ?? '';
        }

        //dd($recibo_historial);
        $configuracion = ConfiguracionRecibo::first();

        return view('polizas.residencia.recibo_edit', compact('recibo_historial', 'meses', 'configuracion'));
    }

    public function get_recibo_update(Request $request)
    {
        //modificación de ultimo recibo
        $id = $request->id_residencia_detalle;
        $detalle = DetalleResidencia::findOrFail($id);

        $residencia = Residencia::findOrFail($detalle->Residencia);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $impresion_recibo = $request->AxoImpresionRecibo . '-' . $request->MesImpresionRecibo . '-' . $request->DiaImpresionRecibo;

        $recibo_historial_anterior = ResidenciaHistorialRecibo::where('PolizaResidenciaDetalle', $id)->orderBy('id', 'desc')->first();


        $recibo_historial = new ResidenciaHistorialRecibo();
        $recibo_historial->PolizaResidenciaDetalle = $id;
        //este valor cambia por eso no se manda al metodo de save_recibo
        $recibo_historial->ImpresionRecibo = Carbon::parse($impresion_recibo)->format('Y-m-d');
        $recibo_historial->NombreCliente = $request->NombreCliente;
        $recibo_historial->NitCliente = $request->NitCliente;
        $recibo_historial->DireccionResidencia = $request->DireccionResidencia;
        $recibo_historial->NumeroRecibo = $detalle->NumeroRecibo;
        $recibo_historial->CompaniaAseguradora = $request->CompaniaAseguradora;
        $recibo_historial->ProductoSeguros = $request->ProductoSeguros;
        $recibo_historial->NumeroPoliza = $request->NumeroPoliza;
        $recibo_historial->VigenciaDesde = $request->VigenciaDesde;
        $recibo_historial->VigenciaHasta = $request->VigenciaHasta;
        $recibo_historial->FechaInicio = $request->FechaInicio;
        $recibo_historial->FechaFin = $request->FechaFin;
        $recibo_historial->Anexo = $request->Anexo;
        $recibo_historial->Referencia = $request->Referencia;
        $recibo_historial->FacturaNombre = $request->FacturaNombre;

        $recibo_historial->FechaVencimiento = $request->FechaVencimiento ?? $detalle->FechaInicio;
        $recibo_historial->NumeroCorrelativo = $request->NumeroCorrelativo ??  '01';
        $recibo_historial->Cuota = $request->Cuota ?? '01/01';
        // dd($request->FechaVencimiento);


        // 🔹 Copiar campos del recibo anterior (si existe)
        if ($recibo_historial_anterior) {
            $recibo_historial->Departamento        = $recibo_historial_anterior->Departamento;
            $recibo_historial->Municipio           = $recibo_historial_anterior->Municipio;
            $recibo_historial->MontoCartera        = $recibo_historial_anterior->MontoCartera;
            $recibo_historial->PrimaCalculada      = $recibo_historial_anterior->PrimaCalculada;
            $recibo_historial->Descuento           = $recibo_historial_anterior->Descuento;
            $recibo_historial->SubTotal            = $recibo_historial_anterior->SubTotal;
            $recibo_historial->PordentajeDescuento = $recibo_historial_anterior->PordentajeDescuento ?? 0;
            $recibo_historial->PrimaDescontada     = $recibo_historial_anterior->PrimaDescontada;
            $recibo_historial->ValorCCF            = $recibo_historial_anterior->ValorCCF;
            $recibo_historial->TotalAPagar         = $recibo_historial_anterior->TotalAPagar;
            $recibo_historial->TasaComision        = $recibo_historial_anterior->TasaComision;
            $recibo_historial->Comision            = $recibo_historial_anterior->Comision;
            $recibo_historial->IvaSobreComision    = $recibo_historial_anterior->IvaSobreComision;
            $recibo_historial->SubTotalComision    = $recibo_historial_anterior->SubTotalComision;
            $recibo_historial->Retencion           = $recibo_historial_anterior->Retencion;
            $recibo_historial->ValorCCF            = $recibo_historial_anterior->ValorCCF;
            $recibo_historial->Otros               = $recibo_historial_anterior->Otros ?? 0;
        }
        $recibo_historial->Usuario = auth()->user()->id;

        $recibo_historial->save();
        // dd($recibo_historial);

        //dd("insert");
        // alert()->success('Actualizacion de Recibo Exitoso');
        //enviar a descargar el archivo
        $cliente = Cliente::findOrFail($residencia->Asegurado);
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $configuracion = ConfiguracionRecibo::first();
        $exportar = 1;
        $pdf = \PDF::loadView('polizas.residencia.recibo', compact('configuracion', 'cliente', 'recibo_historial', 'detalle', 'residencia', 'meses', 'exportar'))->setWarnings(false)->setPaper('letter');
        //  dd($detalle);
        return $pdf->stream('Recibos.pdf');

        return redirect('polizas/residencia/' . $residencia->Id . '/edit');
    }
}
