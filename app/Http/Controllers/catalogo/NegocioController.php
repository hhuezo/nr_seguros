<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\ClienteEstado;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoVenta;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Genero;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\NegocioAccidente;
use App\Models\catalogo\NegocioAuto;
use App\Models\catalogo\NegocioDineroValores;
use App\Models\catalogo\NegocioEquipoElectronico;
use App\Models\catalogo\NegocioGastosMedicos;
use App\Models\catalogo\NegocioIncendio;
use App\Models\catalogo\NegocioOtros;
use App\Models\catalogo\NegocioRoboHurto;
use App\Models\catalogo\NegocioVida;
use App\Models\catalogo\NegocioVideDeuda;
use App\Models\catalogo\NegocioVideDeudaCobertura;
use App\Models\catalogo\Parentesco;
use App\Models\catalogo\TipoCartera;
use App\Models\catalogo\TipoNegocio;
use App\Models\catalogo\TipoPoliza;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NegocioController extends Controller
{

    public function index()
    {
        $negocios = Negocio::where('Activo', 1)->get();
        return view('catalogo.negocio.index', compact('negocios'));
    }

    public function create()
    {
        session(['tab1' => 1]);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $tipos_poliza = TipoPoliza::where('Activo', '=', 1)->get();
        $tipos_negocio = TipoNegocio::where('Activo', '=', 1)->get();
        $estados_venta = EstadoVenta::where('Activo', '=', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', '=', 1)->get();
        $necesidad_proteccion = NecesidadProteccion::where('Activo', 1)->get();
        $forma_pago = FormaPago::where('Activo', 1)->get();
        $genero = Genero::where('Activo', 1)->get();
        $cobertura = NegocioVideDeudaCobertura::where('Activo', 1)->get();
        $tipo_cartera = TipoCartera::where('Activo', 1)->get();
        $parentesco = Parentesco::where('Activo', 1)->get();
        $cliente_estado = ClienteEstado::get();

        return view('catalogo.negocio.create', compact('cliente_estado', 'parentesco', 'tipo_cartera', 'cobertura', 'genero', 'forma_pago', 'aseguradoras', 'tipos_poliza', 'tipos_negocio', 'estados_venta', 'ejecutivos', 'necesidad_proteccion'));
    }

    public function store(Request $request)
    {
        $time = Carbon::now();
        //diferenciar al tipo de cliente
        if ($request->TipoPersona == 1) { //cliente natural
            $cliente = Cliente::where('Dui', $request->Dui)->first();
        } else {
            $cliente = Cliente::where('Nit', $request->NitEmpresa)->first();
        }
        if (!$cliente) {
            $cliente = new Cliente();
            $cliente->TipoPersona = $request->TipoPersona;
            if ($request->TipoPersona == 1) {
                $cliente->Dui = $request->Dui;
            } else {
                $cliente->Nit = $request->NitEmpresas;
            }
            $cliente->Nombre = $request->NombreCliente;
            $cliente->FormaPago = $request->FormaPago;
            $cliente->Estado = 1;
            $cliente->CorreoPrincipal=$request->Email;

            $cliente->save();
        }
        $negocio = new Negocio();
        $negocio->NecesidadProteccion = $request->NecesidadProteccion;
        $negocio->InicioVigencia = $request->InicioVigencia;
        $negocio->TipoNecesidad = $request->TipoNecesidad;
        $negocio->Asegurado = $cliente->Id;
        $negocio->FechaVenta = $request->FechaVenta;
        $negocio->TipoPoliza = $request->TipoPoliza;
        $negocio->SumaAsegurada = $request->SumaAsegurada;
        $negocio->Prima = $request->Prima;
        $negocio->Observacion = $request->Observacion;
        $negocio->TipoNegocio = $request->TipoNegocio;
        $negocio->NumCuotas = $request->NumCuotas;
        $negocio->EstadoVenta = $request->EstadoVenta;
        $negocio->Ejecutivo = $request->Ejecutivo;
        $negocio->FechaIngreso = $time->toDateTimeString();
        $negocio->UsuarioIngreso = auth()->user()->id;
        $negocio->NumeroPoliza=$request->NumeroPoliza;
        $negocio->PlanTipoProducto=$request->PlanTipoProducto;
        $negocio->DepartamentoAtiende=$request->DepartamentoAtiende;
        $negocio->MetodoPago=$request->MetodoPago;
        $negocio->save();

        $string = $request->ModalAseguradora;
        $id = explode(",", $string);

        if ($request->NecesidadProteccion == 1) { //auto
            NegocioAuto::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 2) { //incendio
            NegocioIncendio::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 3) {
            NegocioDineroValores::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 4 || $request->NecesidadProteccion == 6) {
            NegocioOtros::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 7) {
            NegocioEquipoElectronico::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 8) {
            NegocioRoboHurto::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 10) {
            NegocioGastosMedicos::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 11) {
            NegocioVida::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 13) {
            NegocioAccidente::whereIn('Id', $id)->update(['Negocio', $negocio->Id]);
        } else if ($request->NecesidadProteccion == 7) {

        }

        alert()->success('El registro ha sido creado correctamente');
        return back();
    }

    public function store_aseguradora(Request $request)
    {
        //dividir los campos por tablas
        if ($request->NecesidadProteccion == 1) {
            $auto = new NegocioAuto();
            $auto->Aseguradora = $request->Aseguradora;
            $auto->SumaAsegurada = $request->SumaAsegurada;
            $auto->Marca = $request->Marca;
            $auto->Modelo = $request->Modelo;
            $auto->Axo = $request->Axo;
            $auto->Placa = $request->Placa;

            $auto->Prima = $request->Prima;
            $auto->Cantidad = $request->Cantidad;
            $auto->save();
            return $auto->Id;

        } else if ($request->NecesidadProteccion == 2) {
            $incendio = new NegocioIncendio();
            $incendio->Direccion = $request->Direccion;
            $incendio->Giro = $request->Giro;
            $incendio->ValorConstruccion = $request->ValorConstruccion;
            $incendio->ValorContenido = $request->ValorContenido;
            $incendio->Aseguradora = $request->Aseguradora;
            $incendio->SumaAsegurada = $request->SumaAsegurada;
            $incendio->Prima = $request->Prima;
            $incendio->save();
            return $incendio->Id;

        } else if ($request->NecesidadProteccion == 3) {
            $dinero = new NegocioDineroValores();
            $dinero->Aseguradora = $request->Aseguradora;
            $dinero->SumaAsegurada = $request->SumaAsegurada;

            $dinero->Prima = $request->Prima;
            $dinero->save();
            return $dinero->Id;

        } else if ($request->NecesidadProteccion == 4 || $request->NecesidadProteccion == 6) {
            $otros = new NegocioOtros();
            $otros->Aseguradora = $request->Aseguradora;
            $otros->SumaAsegurada = $request->SumaAsegurada;

            $otros->Prima = $request->Prima;
            $otros->save();
            return $otros->Id;

        } else if ($request->NecesidadProteccion == 7) {
            $equipo = new NegocioEquipoElectronico();
            $equipo->Aseguradora = $request->Aseguradora;
            $equipo->SumaAsegurada = $request->SumaAsegurada;

            $equipo->Prima = $request->Prima;
            $equipo->save();
            return $equipo->Id;

        } else if ($request->NecesidadProteccion == 8) {
            $robo = new NegocioRoboHurto();
            $robo->Aseguradora = $request->Aseguradora;
            $robo->SumaAsegurada = $request->SumaAsegurada;

            $robo->Prima = $request->Prima;
            $robo->save();
            return $robo->Id;

        } else if ($request->NecesidadProteccion == 13) {
            $accidente = new NegocioAccidente();
            $accidente->Aseguradora = $request->Aseguradora;
            $accidente->SumaAsegurada = $request->SumaAsegurada;
            $accidente->FechaNacimiento = $request->FechaNacimiento;
            $accidente->Cantidad = $request->Cantidad;
            $accidente->Genero = $request->Genero;
            $accidente->Prima = $request->Prima;
            $accidente->save();
            return $accidente->Id;

        } elseif ($request->NecesidadProteccion == 10) {
            if ($request->TipoPlan == 1) {
                $gastos = new NegocioGastosMedicos();
                $gastos->Aseguradora = $request->Aseguradora;
                $gastos->SumaAsegurada = $request->SumaAsegurada;
                $gastos->FechaNacimiento = $request->FechaNacimiento;
                $gastos->Genero = $request->Genero;
                if ($request->Vida == 'checked') {
                    $gastos->Vida = 1;
                } else {
                    $gastos->Vida = 0;
                }
                if ($request->Dental == 'checked') {
                    $gastos->Dental = 1;
                } else {
                    $gastos->Dental = 0;
                }

                $gastos->Prima = $request->Prima;

                $gastos->save();
            } else if ($request->TipoPlan == 2) {
                $gastos = new NegocioGastosMedicos();
                $gastos->Aseguradora = $request->Aseguradora;
                $gastos->SumaAsegurada = $request->SumaAsegurada;
                $gastos->CantidadPersonas = $request->CantidadPersona;
                $gastos->Contributivo = $request->Contributivo;
                $gastos->MaximoVitalicio = $request->MaximoVitalicio;
                $gastos->CantidadTitulares = $request->CantidadTitulares;
                $gastos->save();
            } else if ($request->TipoPlan == 3) {
                $gastos = new NegocioGastosMedicos();
                $gastos->Aseguradora = $request->Aseguradora;
                $gastos->SumaAsegurada = $request->SumaAsegurada;

                $gastos->Prima = $request->Prima;
                $gastos->save();
                //guarda los familiares de gastos medicos
            }
            return $gastos->Id;

        } elseif ($request->NecesidadProteccion == 11) {
            $vida = new NegocioVida();
            $vida->Aseguradora = $request->Aseguradora;
            $vida->SumaAsegurada = $request->SumaAsegurada;
            $vida->FechaNacimiento = $request->FechaNacimiento;
            $vida->Genero = $request->Genero;
            if ($request->Fumador == 'checked') {
                $vida->Fumador = 1;
            } else {
                $vida->Fumador = 0;
            }
            if ($request->InvalidezParcial == 'checked') {
                $vida->InvalidezParcial = 1;
            } else {
                $vida->InvalidezParcial = 0;
            }
            if ($request->InvalidezTotal == 'checked') {
                $vida->InvalidezTotal = 1;
            } else {
                $vida->InvalidezTotal = 0;
            }
            if ($request->GastosFunerarios == 'checked') {
                $vida->GastosFunerarios = 1;
            } else {
                $vida->GastosFunerarios = 0;
            }
            $vida->EnfermedadesGraves = $request->EnfermedadesGraves;
            $vida->Termino = $request->Termino;
            $vida->Ahorro = $request->Ahorro;
            $vida->Plazo = $request->Plazo;
            $vida->SesionBeneficios = $request->SesionBeneficio;
            $vida->Coberturas = $request->Cobertura;

            $vida->Prima = $request->Prima;
            $vida->save();
            return $vida->Id;

        } elseif ($request->NecesidadProteccion == 14) {
            $videuda = new NegocioVideDeuda();
            $videuda->Aseguradora = $request->Aseguradora;
            $videuda->SumaAsegurada = $request->SumaAsegurada;
            $videuda->Coberturas = $request->Cobertura;
            $videuda->TipoCartera = $request->TipoCartera;

            $videuda->Prima = $request->Prima;
            $videuda->save();
            return $videuda->Id;
        }
    }

    public function get_aseguradoras(Request $request)
    {
        $aseguradora = array();
        $string = $request->ModalAseguradora;
        $id = explode(",", $string);
        $auto = NegocioAuto::whereIn('Id', $id)->get();

        if ($auto) {
            foreach ($auto as $obj) {
                array_push($aseguradora, array(
                    'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Auto', 'SumaAsegurada' => $obj->SumaAsegurada,
                    'Prima' => $obj->Prima, 'Id' => $obj->Id,
                ));
            }
        } else {
            $incendio = NegocioIncendio::whereIn('Id', $id)->get();
            if ($incendio) {
                foreach ($incendio as $obj) {
                    array_push($aseguradora, array(
                        'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Incendio', 'SumaAsegurada' => $obj->SumaAsegurada,
                        'Prima' => $obj->Prima, 'Id' => $obj->Id,
                    ));
                }
            } else {
                $dinero = NegocioDineroValores::whereIn('Id', $id)->get();
                if ($dinero) {
                    foreach ($dinero as $obj) {
                        array_push($aseguradora, array(
                            'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Dinero y Valores', 'SumaAsegurada' => $obj->SumaAsegurada,
                            'Prima' => $obj->Prima, 'Id' => $obj->Id,
                        ));
                    }
                } else {
                    $otros = NegocioOtros::whereIn('Id', $id)->get();
                    if ($otros) {
                        foreach ($otros as $obj) {
                            array_push($aseguradora, array(
                                'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Otros', 'SumaAsegurada' => $obj->SumaAsegurada,
                                'Prima' => $obj->Prima, 'Id' => $obj->Id,
                            ));
                        }
                    } else {
                        $equipo = NegocioEquipoElectronico::whereIn('Id', $id)->get();
                        if ($equipo) {
                            foreach ($equipo as $obj) {
                                array_push($aseguradora, array(
                                    'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Equipo Electronico', 'SumaAsegurada' => $obj->SumaAsegurada,
                                    'Prima' => $obj->Prima, 'Id' => $obj->Id,
                                ));
                            }
                        } else {
                            $robo = NegocioRoboHurto::whereIn('Id', $id)->get();
                            if ($robo) {
                                foreach ($robo as $obj) {
                                    array_push($aseguradora, array(
                                        'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Robo y Hurto', 'SumaAsegurada' => $obj->SumaAsegurada,
                                        'Prima' => $obj->Prima, 'Id' => $obj->Id,
                                    ));
                                }
                            } else {
                                $accidente = NegocioAccidente::whereIn('Id', $id)->get();
                                if ($accidente) {
                                    foreach ($accidente as $obj) {
                                        array_push($aseguradora, array(
                                            'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Accidentes Personales', 'SumaAsegurada' => $obj->SumaAsegurada,
                                            'Prima' => $obj->Prima, 'Id' => $obj->Id,
                                        ));
                                    }
                                } else {
                                    $gastos = NegocioGastosMedicos::whereIn('Id', $id)->get();
                                    if ($gastos) {
                                        foreach ($gastos as $obj) {
                                            array_push($aseguradora, array(
                                                'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Gastos Medicos', 'SumaAsegurada' => $obj->SumaAsegurada,
                                                'Prima' => $obj->Prima, 'Id' => $obj->Id,
                                            ));
                                        }
                                    } else {
                                        $vida = NegocioVida::whereIn('Id', $id)->get();
                                        if ($vida) {
                                            foreach ($vida as $obj) {
                                                array_push($aseguradora, array(
                                                    'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Vida', 'SumaAsegurada' => $obj->SumaAsegurada,
                                                    'Prima' => $obj->Prima, 'Id' => $obj->Id,
                                                ));
                                            }
                                        } else {
                                            $videuda = NegocioVideDeuda::whereIn('Id', $id)->get();
                                            if ($videuda) {
                                                foreach ($videuda as $obj) {
                                                    array_push($aseguradora, array(
                                                        'Aseguradora' => $obj->aseguradora->Nombre, 'NecesidadProteccion' => 'Vida Deuda', 'SumaAsegurada' => $obj->SumaAsegurada,
                                                        'Prima' => $obj->Prima, 'Id' => $obj->Id,
                                                    ));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('catalogo/negocio/aseguradoras', compact('aseguradora'));
        //return view('polizas.deuda.requisitos', compact('requisitos'));

    }

    public function show($id)
    {

        dd("holi show");
        // $ejecutivo = Ejecutivo::where('Activo', '1')->get();
        // return view('catalogo.negocio.show', compact('ejecutivo'));

    }

    public function consultar(Request $request)
    {

        // $negocio = Negocio::with('aseguradora')->whereBetween('FechaVenta', [$request->FechaInicio, $request->FechaFinal])->get();
        // dd($negocio);
        // return view('catalogo.negocio.consulta', compact('negocio'));

    }

    public function edit($id)
    {
        $negocio = Negocio::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $tipos_poliza = TipoPoliza::where('Activo', '=', 1)->get();
        $tipos_negocio = TipoNegocio::where('Activo', '=', 1)->get();
        $estados_venta = EstadoVenta::where('Activo', '=', 1)->get();
        $ejecutivos = Ejecutivo::where('Activo', '=', 1)->get();

        return view('catalogo.negocio.edit', compact('negocio', 'aseguradoras', 'tipos_poliza', 'tipos_negocio', 'estados_venta', 'ejecutivos'));
    }

    public function update(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $negocio->Asegurado = $request->Asegurado;
        $negocio->Aseguradora = $request->Aseguradora;
        $negocio->FechaVenta = $request->FechaVenta;
        $negocio->TipoPoliza = $request->TipoPoliza;
        $negocio->InicioVigencia = $request->InicioVigencia;
        $negocio->SumaAsegurada = $request->SumaAsegurada;
        $negocio->Prima = $request->Prima;
        $negocio->Observacion = $request->Observacion;
        $negocio->TipoNegocio = $request->TipoNegocio;
        $negocio->EstadoVenta = $request->EstadoVenta;
        $negocio->Ejecutivo = $request->Ejecutivo;
        $negocio->update();
        alert()->success('El registro ha sido modificado correctamente');
        return back();
    }

    public function destroy($id)
    {
        Negocio::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
    }

    public function getCliente(Request $request)
    {
        //obtener el cliente
        if ($request->tipoPersona == 1) {
            $cliente = Cliente::where('Dui', $request->Dui)->first();
        } else {
            $cliente = Cliente::where('Nit', $request->Nit)->first();
        }
        if ($cliente) {
            ?>
            <script>
                document.getElementById('Dui').style.backgroundColor = '#ff3f33';
                document.getElementById('Dui').style.color = '#ffffff';
                document.getElementById('NitEmpresa').style.backgroundColor = '#ff3f33';
                document.getElementById('NitEmpresa').style.color = '#ffffff';
                document.getElementById('NombreCliente').value = <?php echo json_encode($cliente->Nombre); ?>;
                document.getElementById('Email').value = <?php echo json_encode($cliente->CorreoPrincipal); ?>;

                //document.getElementById('FormaPago').value = <?php echo json_encode($cliente->formas_pago); ?>;
            </script>
        <?php

        } else {
            ?>
            <script>
                document.getElementById('Dui').style.backgroundColor = '';
                document.getElementById('Dui').style.color = '';
                document.getElementById('NitEmpresa').style.backgroundColor = '';
                document.getElementById('NitEmpresa').style.color = '';
                document.getElementById('NombreCliente').value = "";
                document.getElementById('Email').value = "";

               // document.getElementById('FormaPago').value = "";
            </script>
<?php
}
        /*
    $programacion = Programacion::with('formaPago')->where('Referencia', '=', $request->get('Referencia'))->first();
    if ($programacion) {
    if ($programacion->Estado == 5) { //abierto
    $cerrada = 'NO';
    $liquidada = 'NO';
    } elseif ($programacion->Estado == 6) {   //cerrada
    $cerrada = 'SI';
    $liquidada = 'NO';
    } else {    //liquidada
    $cerrada = 'SI';
    $liquidada = 'SI';
    }
    switch ($programacion->Cetia) {
    case (2):       // santa ana
    $SiglasUfi = 'LVSA';
    break;
    case (4):     //paracentral
    $SiglasUfi = 'LVSP';
    break;
    case (5):        //usulutan
    $SiglasUfi = 'LVUSU';
    break;
    case (6):    //san miguel
    $SiglasUfi = 'LVSM';
    break;
    default:      //oficina central o cetia II
    $SiglasUfi = 'LVRCO';
    }

    if ($programacion->Reintegrada == 1) {
    $reintegrada = 'SI';
    } else {
    $reintegrada = 'NO';
    }

    if (!$programacion->FechaCheque) {
    $programacion->FechaCheque = '';
    }

    if (!$programacion->FechaRemesa) {
    $programacion->FechaRemesa = '';
    }
    if (!$programacion->FechaRemesa1) {
    $programacion->FechaRemesa1 = '';
    }
    if (!$programacion->FechaContable) {
    if ($programacion->Cetia == 1) {
    //oficina central
    $periodoContable = PeriodoContable::where('Cetia', '=', 1)->where('Activo', '=', 1)->first();
    $programacion->FechaContable = $periodoContable->Fecha;
    } else {
    //cetias
    $periodoContable = PeriodoContable::where('Cetia', '=', 2)->where('Activo', '=', 1)->first();
    $programacion->FechaContable = $periodoContable->Fecha;
    }
    }
    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    //    dd($programacion);

    ?>
    <script>
    document.getElementById('cerrada').value = '<?php echo $cerrada; ?>';
    document.getElementById('FormaPagoSiglas').value = '<?php echo $programacion->formaPago->Codigo; ?>';
    document.getElementById('FormaPago').value = '<?php echo $programacion->formaPago->Nombre; ?>';
    document.getElementById('Cetia').value = '<?php echo $programacion->cetia->Nombre; ?>';
    document.getElementById('Periodo').value = 'del <?php echo date('d/m/Y', strtotime($programacion->FechaInicio)) . ' al ' . date('d/m/Y', strtotime($programacion->FechaFinal)); ?>';
    document.getElementById('SiglasUfi').value = '<?php echo $SiglasUfi; ?>-';
    document.getElementById('Axo').value = '-<?php echo date('Y'); ?>';
    document.getElementById('ReferenciaUfi').value = '<?php echo $programacion->ReferenciaUfi; ?>';
    document.getElementById('NumeroCheque').value = '<?php echo $programacion->NoCheque; ?>';
    document.getElementById('NoFolio').value = '<?php echo $programacion->NoFolio; ?>';
    document.getElementById('CantidadRemesa').value = '<?php echo $programacion->CantidadRemesa; ?>';
    document.getElementById('NoFolio1').value = '<?php echo $programacion->NoFolio1; ?>';
    document.getElementById('CantidadRemesa1').value = '<?php echo $programacion->CantidadRemesa1; ?>';
    document.getElementById('Liquidada').value = '<?php echo $liquidada; ?>';
    document.getElementById('Reintegrada').value = '<?php echo $reintegrada; ?>';
    document.getElementById('MesPeriodo').value = '<?php echo $meses[date('n', strtotime($programacion->FechaContable))]; ?>';
    document.getElementById('AxoPeriodo').value = '<?php echo date('Y', strtotime($programacion->FechaContable)); ?>';
    document.getElementById('FechaCheque').value = '<?php echo date('d/m/Y', strtotime($programacion->FechaCheque)); ?>';
    document.getElementById('FechaRemesa1').value = '<?php echo date('d/m/Y', strtotime($programacion->FechaRemesa)); ?>';
    document.getElementById('FechaRemesa').value = '<?php echo date('d/m/Y', strtotime($programacion->FechaRemesa1)); ?>';
    </script>
    <?php
    } else {

    return response()->json(['mensaje' => 'la referencia ufi no existe', 'title' => 'Error!', 'icon' => 'error', 'showConfirmButton' => 'true']);
    }
     */
    }
}
