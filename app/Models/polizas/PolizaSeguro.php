<?php

namespace App\Models\polizas;

use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\FormaPagoPoliza;
use App\Models\catalogo\MotivoCancelacion;
use App\Models\catalogo\Negocio;
use App\Models\catalogo\NrCartera;
use App\Models\catalogo\Plan;
use App\Models\catalogo\Producto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguro extends Model
{
    use HasFactory;
    protected $table = 'poliza_seguro';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'Oferta',
        'NumeroVigencia',
        'FormaPago',
        'NumCuotas',
        'NumeroPoliza',
        'EstadoPoliza',
        'Productos',
        'Planes',
        'SumaAsegurada',
        'PrimaNetaAnual',
        'PorcentajeComisionNR',
        'Cliente',
        'VigenciaDesde',
        'VigenciaHasta',
        'DiasVigencia',
        'MotivoCancelacion',
        'FechaCancelacion',
        'CodCancelacion',
        'FechaEnvioAnexo',
        'Observacion',
        'DatosRamo',
        'SolicitudRenovacion',
        'OrigenPoliza',
        'FechaVinculacion',
        'Departamento',
        'TipoCarteraNR',
        'FechaRecepcion',
        'SustituidaPoliza',
        'ClausulasEspeciales',
        'BeneficiosAdicionales',
        'Comentarios',
        'IvaIncluido',
        'PorcentajeDescuentoRentabilidad',
        'PorcentajeDescuentoBuenaExperiencia',
        'PorcentajeOtrosDescuentos',
        'PorcentajeComsionCliente',
        'ObservacionSiniestro',
        'EjecutivoCia',
        'GrupoCliente',
        'Deducible',
        'ValorDeducible',
        'Activo',
        'Usuario',

    ];

    public function oferta()
    {
        return $this->belongsTo(Negocio::class, 'Oferta', 'Id');
    }

    public function forma_pago()
    {
        return $this->belongsTo(FormaPagoPoliza::class, 'FormaPago', 'Id');
    }

    public function estado_polizas()
    {
        return $this->belongsTo(EstadoPoliza::class, 'EstadoPoliza', 'Id');
    }

    public function cancelacion()
    {
        return $this->belongsTo(MotivoCancelacion::class, 'CodCancelacion', 'Id');
    }

    public function departamento()
    {
        return $this->belongsTo(DepartamentoNR::class, 'Departamento', 'Id');
    }

    public function tipoCarteraNr()
    {
        return $this->belongsTo(NrCartera::class, 'TipoCarteraNR', 'Id');
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class, 'Cliente', 'Id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Productos', 'Id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'Planes', 'Id');
    }

    public function ejecutivoCia()
    {
        return $this->belongsTo(Ejecutivo::class, 'EjecutivoCia', 'Id');
    }

    public function certificados()
    {
        return $this->hasMany(PolizaSeguroCertificado::class, 'PolizaSeguroId', 'Id')
            ->where('Activo', 1)
            ->orderBy('NumeroCertificado', 'asc');
    }

    public function renovaciones()
    {
        return $this->hasMany(PolizaSeguroRenovacion::class, 'PolizaSeguroId', 'Id')
            ->where('Activo', 1)
            ->orderBy('FechaRegistro', 'desc')
            ->orderBy('Id', 'desc');
    }
}
