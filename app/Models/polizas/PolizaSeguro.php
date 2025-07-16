<?php

namespace App\Models\polizas;

use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cancelacion;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Departamento;
use App\Models\catalogo\DepartamentoNR;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\FormaPago;
use App\Models\catalogo\Negocio;
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
        'FormaPago',
        'NumeroPoliza',
        'EstadoPoliza',
        'Productos',
        'Planes',
        'Cliente',
        'VigenciaDesde',
        'VigenciaHasta',
        'DiasVigencia',
        'MotivoCancelacion',
        'FechaCancelacion',
        'CodCancelacion',
        'FechaEnvioAnexo',
        'Observacion',
        'SolicitudRenovacion',
        'OrigenPoliza',
        'FechaVinculacion',
        'Departamento',
        'FechaRecepcion',
        'SustituidaPoliza',
        'ObservacionSiniestro',
        'EjecutivoCia',
        'GrupoCliente',
        'Deducible',
        'Activo',
        'Usuario',

    ];

    public function oferta()
    {
        return $this->belongsTo(Negocio::class, 'Oferta', 'Id');
    }

    public function forma_pago()
    {
        return $this->belongsTo(FormaPago::class, 'FormaPago', 'Id');
    }

    public function estado_polizas()
    {
        return $this->belongsTo(EstadoPoliza::class, 'EstadoPoliza', 'Id');
    }

    public function cancelacion()
    {
        return $this->belongsTo(Cancelacion::class, 'CodCancelacion', 'Id');
    }

    public function departamento()
    {
        return $this->belongsTo(DepartamentoNR::class, 'Departamento', 'Id');
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

    public function coberturas()
    {
        return $this->hasMany(PolizaSeguroCobertura::class, 'PolizaSeguroId', 'Id');
    }

    public function datosTecnicos()
    {
        return $this->hasMany(PolizaSeguroDatosTecnicos::class, 'PolizaSeguroId', 'Id');
    }
}
