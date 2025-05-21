<?php

namespace App\Models\suscripcion;

use App\Models\polizas\Comentario;
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
        'Gestor',
        'CompaniaId',
        'Contratante',
        'PolizaDeuda',
        'PolizaVida',
        'Asegurado',
        'Dui',
        'Edad',
        'Genero',
        'SumaAseguradaDeuda',
        'SumaAseguradaVida',
        'TipoClienteId',
        'Imc',
        'TipoIMCId',
        'Padecimiento',
        'TipoOrdenMedicaId',
        'EstadoId ',
        'ResumenGestion',
        'FechaReportadoCia',
        'TareasEvaSisa',
        'FechaResolucion',
        'ResolucionFinal',
        'ValorExtraPrima',
        'Activo'
    ];

    protected $guarded = [];

    public function compania()
    {
        return $this->belongsTo(Compania::class, 'CompaniaId');
    }

    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'TipoClienteId');
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

    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'SuscripcionId');
    }
}
