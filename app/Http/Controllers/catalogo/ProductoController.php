<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cobertura;
use App\Models\catalogo\CoberturaTarificacion;
use App\Models\catalogo\DatosTecnicos;
use App\Models\catalogo\NecesidadProteccion;
use App\Models\catalogo\Producto;
use App\Models\catalogo\ProductoCertificadoCampo;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    private function validacionesCampoCertificado(): array
    {
        return [
            'ninguna',
            'dui',
            'solo_numeros',
            'solo_numeros_letras',
            'solo_texto',
            'correo',
        ];
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idRegistro = $request->idRegistro ?? 0;

        $productos = Producto::where('Activo', 1)->orderBy('Id', 'asc')->get();

        $posicion = 0;
        if ($idRegistro > 0) {
            $indice = $productos->search(function ($p) use ($idRegistro) {
                return $p->Id == $idRegistro;
            });

            if ($indice !== false) {
                $pageLength = 10;
                $posicion = floor($indice / $pageLength) * $pageLength;
            }
        }

        return view('catalogo.producto.index', compact('productos', 'posicion'));
    }


    public function create()
    {

        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();

        return view('catalogo.producto.create', compact('aseguradoras', 'ramos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Aseguradora' => 'required|exists:aseguradora,Id',
            'NecesidadProteccion' => 'required|exists:necesidad_proteccion,Id',
            'PorcentajeComisionNoDeclarativa' => 'nullable|numeric|min:0|max:100',
        ]);

        $producto = new Producto();
        $producto->Nombre = $request->Nombre;
        $producto->Aseguradora = $request->Aseguradora;
        $producto->NecesidadProteccion = $request->NecesidadProteccion;
        $producto->Descripcion = $request->Descripcion;
        $producto->PorcentajeComisionNoDeclarativa = $request->PorcentajeComisionNoDeclarativa !== null && $request->PorcentajeComisionNoDeclarativa !== ''
            ? $request->PorcentajeComisionNoDeclarativa
            : null;
        $producto->Activo = 1;
        $producto->save();

        session(['tab1' => '1']);

        alert()->success('El registro ha sido creado correctamente');
        return redirect('catalogo/producto/' . $producto->Id . '/edit');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (session()->has('tab2')) {
            session(['tab1' => session('tab2')]);
            session(['tab2' => '1']);
        } else {
            session(['tab1' => '1']);
        }


        $producto = Producto::findOrFail($id);
        $aseguradoras = Aseguradora::where('Activo', '=', 1)->get();
        $ramos = NecesidadProteccion::where('Activo', '=', 1)->get();
        $coberturas = Cobertura::where('Activo', '=', 1)->where('Producto', '=', $producto->Id)->get();
        $datos_tecnicos = DatosTecnicos::where('Activo', '=', 1)->where('Producto', '=', $producto->Id)->get();
        $certificado_campos = ProductoCertificadoCampo::where('Activo', 1)
            ->where('Producto', $producto->Id)
            ->orderBy('Orden', 'asc')
            ->orderBy('Id', 'asc')
            ->get();
        $tarificaciones = CoberturaTarificacion::where('Activo',1)->get();
        return view('catalogo/producto/edit', compact(
            'producto',
            'aseguradoras',
            'ramos',
            'coberturas',
            'datos_tecnicos',
            'certificado_campos',
            'tarificaciones'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Aseguradora' => 'required|exists:aseguradora,Id',
            'NecesidadProteccion' => 'required|exists:necesidad_proteccion,Id',
            'PorcentajeComisionNoDeclarativa' => 'nullable|numeric|min:0|max:100',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->Nombre = $request->Nombre;
        $producto->Aseguradora = $request->Aseguradora;
        $producto->NecesidadProteccion = $request->NecesidadProteccion;
        $producto->Descripcion = $request->Descripcion;
        $producto->PorcentajeComisionNoDeclarativa = $request->PorcentajeComisionNoDeclarativa !== null && $request->PorcentajeComisionNoDeclarativa !== ''
            ? $request->PorcentajeComisionNoDeclarativa
            : null;
        $producto->update();
        session(['tab1' => '1']);
        alert()->success('El registro ha sido modificado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras/' . $id . 'edit');
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido desactivado correctamente');
        return back();
        //return Redirect::to('catalogo/aseguradoras');
    }

    public function add_cobertura(Request $request)
    {
        $cobertura = new Cobertura();
        $cobertura->Nombre = $request->Nombre;
        $cobertura->Tarificacion = $request->Tarificacion;
        $cobertura->Descuento = $request->Descuento;
        $cobertura->Iva = $request->Iva;
        $cobertura->Producto  = $request->Producto;
        $cobertura->Activo  = 1;

        $cobertura->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function edit_cobertura(Request $request)
    {
        $cobertura = Cobertura::findOrFail($request->Id);

        $cobertura->Nombre = $request->Nombre;
        $cobertura->Tarificacion = $request->Tarificacion;
        $cobertura->Descuento = $request->Descuento;
        $cobertura->Iva = $request->Iva;
        $cobertura->Producto  = $request->Producto;
        $cobertura->update();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function delete_cobertura(Request $request)
    {
        Cobertura::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '2']);
        return back();
    }

    public function add_dato_tecnico(Request $request)
    {
        $dato_tecnico = new DatosTecnicos();
        $dato_tecnico->Nombre = $request->Nombre;
        $dato_tecnico->Descripcion = $request->Descripcion;
        $dato_tecnico->Producto  = $request->Producto;
        $dato_tecnico->Activo  = 1;

        $dato_tecnico->save();
        alert()->success('El registro ha sido creado correctamente');

        session(['tab2' => '3']);
        return back();
    }

    public function edit_dato_tecnico(Request $request)
    {
        $dato_tecnico = DatosTecnicos::findOrFail($request->Id);

        $dato_tecnico->Nombre = $request->Nombre;
        $dato_tecnico->Descripcion = $request->Descripcion;
        $dato_tecnico->Producto  = $request->Producto;
        $dato_tecnico->update();
        alert()->success('El registro ha sido modificado correctamente');

        session(['tab2' => '3']);
        return back();
    }

    public function delete_dato_tecnico(Request $request)
    {
        DatosTecnicos::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('El registro ha sido eliminado correctamente');

        session(['tab2' => '3']);
        return back();
    }

    public function save_certificado_config(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->PermiteDependientesCertificado = $request->PermiteDependientesCertificado ? 1 : 0;
        $producto->update();

        alert()->success('Configuracion de certificado actualizada correctamente');
        session(['tab2' => '4']);
        return back();
    }

    public function add_certificado_campo(Request $request)
    {
        $request->validate([
            'Producto' => 'required|exists:producto,Id',
            'Etiqueta' => 'required|string|max:150',
            'NombreCampo' => 'required|string|max:150',
            'TipoCampo' => 'required|in:text,number,date,select,textarea,email',
            'ValidacionCampo' => 'required|in:' . implode(',', $this->validacionesCampoCertificado()),
            'Orden' => 'nullable|integer|min:1',
            'Requerido' => 'required|in:0,1',
            'MostrarEnReporte' => 'required|in:0,1',
            'OrigenOpciones' => 'nullable|in:manual,catalogo',
            'CatalogoOrigen' => 'nullable|required_if:OrigenOpciones,catalogo|in:parentesco_beneficiario',
            'OpcionesTexto' => 'nullable|string',
            'Placeholder' => 'nullable|string|max:200',
            'Ayuda' => 'nullable|string',
        ]);

        $origenOpciones = $request->OrigenOpciones === 'catalogo' ? 'catalogo' : 'manual';
        $catalogoOrigen = $origenOpciones === 'catalogo' ? $request->CatalogoOrigen : null;
        $opcionesJson = null;
        if ($request->TipoCampo === 'select' && $origenOpciones === 'manual') {
            $opcionesJson = $this->buildOpcionesJson($request->OpcionesTexto);

            if (!$opcionesJson) {
                return back()
                    ->withErrors(['OpcionesTexto' => 'Debe ingresar al menos una opcion cuando el tipo de campo es select.'])
                    ->withInput();
            }
        }

        $campo = new ProductoCertificadoCampo();
        $campo->Producto = $request->Producto;
        $campo->Etiqueta = $request->Etiqueta;
        $campo->NombreCampo = $request->NombreCampo;
        $campo->TipoCampo = $request->TipoCampo;
        $campo->ValidacionCampo = $request->ValidacionCampo;
        $campo->Requerido = (int) $request->Requerido;
        $campo->MostrarEnReporte = (int) $request->MostrarEnReporte;
        $campo->Orden = $request->Orden ?: 1;
        $campo->Placeholder = $request->Placeholder ?: null;
        $campo->Ayuda = $request->Ayuda ?: null;
        $campo->OpcionesJson = $opcionesJson;
        $campo->OrigenOpciones = $request->TipoCampo === 'select' ? $origenOpciones : 'manual';
        $campo->CatalogoOrigen = $request->TipoCampo === 'select' ? $catalogoOrigen : null;
        $campo->Activo = 1;
        $campo->save();

        alert()->success('Campo de certificado creado correctamente');
        session(['tab2' => '4']);
        return back();
    }

    public function edit_certificado_campo(Request $request)
    {
        $request->validate([
            'Id' => 'required|exists:producto_certificado_campos,Id',
            'Etiqueta' => 'required|string|max:150',
            'NombreCampo' => 'required|string|max:150',
            'TipoCampo' => 'required|in:text,number,date,select,textarea,email',
            'ValidacionCampo' => 'required|in:' . implode(',', $this->validacionesCampoCertificado()),
            'Orden' => 'nullable|integer|min:1',
            'Requerido' => 'required|in:0,1',
            'MostrarEnReporte' => 'required|in:0,1',
            'OrigenOpciones' => 'nullable|in:manual,catalogo',
            'CatalogoOrigen' => 'nullable|required_if:OrigenOpciones,catalogo|in:parentesco_beneficiario',
            'OpcionesTexto' => 'nullable|string',
            'Placeholder' => 'nullable|string|max:200',
            'Ayuda' => 'nullable|string',
        ]);

        $origenOpciones = $request->OrigenOpciones === 'catalogo' ? 'catalogo' : 'manual';
        $catalogoOrigen = $origenOpciones === 'catalogo' ? $request->CatalogoOrigen : null;
        $opcionesJson = null;
        if ($request->TipoCampo === 'select' && $origenOpciones === 'manual') {
            $opcionesJson = $this->buildOpcionesJson($request->OpcionesTexto);

            if (!$opcionesJson) {
                return back()
                    ->withErrors(['OpcionesTexto' => 'Debe ingresar al menos una opcion cuando el tipo de campo es select.'])
                    ->withInput();
            }
        }

        $campo = ProductoCertificadoCampo::findOrFail($request->Id);
        $campo->Etiqueta = $request->Etiqueta;
        $campo->NombreCampo = $request->NombreCampo;
        $campo->TipoCampo = $request->TipoCampo;
        $campo->ValidacionCampo = $request->ValidacionCampo;
        $campo->Requerido = (int) $request->Requerido;
        $campo->MostrarEnReporte = (int) $request->MostrarEnReporte;
        $campo->Orden = $request->Orden ?: 1;
        $campo->Placeholder = $request->Placeholder ?: null;
        $campo->Ayuda = $request->Ayuda ?: null;
        $campo->OpcionesJson = $opcionesJson;
        $campo->OrigenOpciones = $campo->TipoCampo === 'select' ? $origenOpciones : 'manual';
        $campo->CatalogoOrigen = $campo->TipoCampo === 'select' ? $catalogoOrigen : null;
        $campo->update();

        alert()->success('Campo de certificado actualizado correctamente');
        session(['tab2' => '4']);
        return back();
    }

    public function heredar_parentesco_certificado(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $campo = ProductoCertificadoCampo::where('Producto', $producto->Id)
            ->where('NombreCampo', 'parentesco')
            ->first();

        if (!$campo) {
            $campo = new ProductoCertificadoCampo();
            $campo->Producto = $producto->Id;
            $campo->Etiqueta = 'Parentesco';
            $campo->NombreCampo = 'parentesco';
            $campo->Orden = ((int) ProductoCertificadoCampo::where('Producto', $producto->Id)->max('Orden')) + 1;
        }

        $campo->TipoCampo = 'select';
        $campo->ValidacionCampo = 'ninguna';
        $campo->Requerido = 1;
        $campo->MostrarEnReporte = 1;
        $campo->Placeholder = null;
        $campo->Ayuda = 'Opciones heredadas del catalogo de parentescos de beneficiarios.';
        $campo->OpcionesJson = null;
        $campo->OrigenOpciones = 'catalogo';
        $campo->CatalogoOrigen = 'parentesco_beneficiario';
        $campo->Activo = 1;
        $campo->save();

        alert()->success('Campo de parentesco heredado correctamente');
        session(['tab2' => '4']);
        return back();
    }

    public function delete_certificado_campo(Request $request)
    {
        $request->validate([
            'Id' => 'required|exists:producto_certificado_campos,Id',
        ]);

        ProductoCertificadoCampo::findOrFail($request->Id)->update(['Activo' => 0]);
        alert()->error('Campo de certificado eliminado correctamente');
        session(['tab2' => '4']);
        return back();
    }

    private function buildOpcionesJson(?string $opcionesTexto): ?string
    {
        if (!$opcionesTexto) {
            return null;
        }

        $opciones = collect(preg_split("/\r\n|\n|\r/", $opcionesTexto))
            ->map(fn($o) => trim((string) $o))
            ->filter()
            ->values()
            ->all();

        return count($opciones) > 0 ? json_encode($opciones, JSON_UNESCAPED_UNICODE) : null;
    }
}
