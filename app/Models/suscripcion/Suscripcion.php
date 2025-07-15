<?php

namespace App\Models\suscripcion;

use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\polizas\Comentario;
use App\Models\polizas\Deuda;
use App\Models\polizas\Vida;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    use HasFactory;
    protected $table = 'suscripcion';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'FechaIngreso',
        'FechaEntregaDocsCompletos',
        'DiasCompletarInfoCliente',
        'GestorId',
        'CompaniaId',
        'Contratante',
        'PolizaDeuda',
        'PolizaVida',
        'Asegurado',
        'OcupacionId',
        'Dui',
        'Edad',
        'Genero',
        'SumaAseguradaDeuda',
        'SumaAseguradaVida',
        'TipoClienteId',
        'TipoCreditoId',
        'Imc',
        'TipoIMCId',
        'Padecimiento',
        'TipoOrdenMedicaId',
        'EstadoId ',
        'ResumenGestion',
        'FechaReportadoCia',
        'TrabajadoEfectuadoDiaHabil',
        'TareasEvaSisa',
        'FechaCierreGestion',
        'FechaResolucion',
        'ResolucionFinal',
        'ValorExtraPrima',
        'FechaEnvioResoCliente',
        'DiasProcesamientoResolucion',
        'Activo',
        'ReprocesoId'
    ];

    protected $casts = [
        'FechaIngreso' => 'datetime',
    ];


    protected $guarded = [];

    public function compania()
    {
        return $this->belongsTo(Aseguradora::class, 'CompaniaId');
    }
    public function ocupacion()
    {
        return $this->belongsTo(Ocupacion::class, 'OcupacionId');
    }
    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'TipoClienteId');
    }
    public function tipoCredito()
    {
        return $this->belongsTo(TipoCredito::class, 'TipoCreditoId');
    }
    public function tipoOrdenMedica()
    {
        return $this->belongsTo(OrdenMedica::class, 'TipoOrdenMedicaId');
    }

    public function tipoImc()
    {
        return $this->belongsTo(TipoImc::class, 'TipoIMCId');
    }

    public function estadoCaso()
    {
        return $this->belongsTo(EstadoCaso::class, 'EstadoId');
    }

    public function resumenGestion()
    {
        return $this->belongsTo(ResumenGestion::class, 'ResumenGestion');
    }

    public function gestor()
    {
        return $this->belongsTo(Ejecutivo::class, 'GestorId');
    }

    public function contratante()
    {
        return $this->belongsTo(Cliente::class, 'ContratanteId');
    }

    public function polizaDeuda()
    {
        return $this->belongsTo(Deuda::class, 'PolizaDeuda');
    }

    public function polizaVida()
    {
        return $this->belongsTo(Vida::class, 'PolizaVida');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'SuscripcionId');
    }
    public function reproceso()
    {
        return $this->hasMany(Reproceso::class, 'ReprocesoId');
    }
}
