<?php

namespace App\Models\polizas;

use App\Models\catalogo\SaldoMontos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaCartera extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
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
        'NombreSociedad',
        'Sexo',
        'FechaOtorgamiento',
        'FechaVencimiento',
        'Ocupacion',
        'NumeroReferencia',
        'MontoOtorgado',
        'SaldoCapital',
        'Intereses',
        'InteresesMoratorios',
        'SaldoTotal',
        'User',
        'Axo',
        'Mes',
        'PolizaDeuda',
        'FechaInicio',
        'FechaFinal',
        'TipoError',
        'FechaNacimientoDate',
        'Edad',
        'InteresesCovid',
        'MontoNominal',
        'LineaCredito',
        'NoValido',
        'PolizaDeudaDetalle'

    ];

    public function poliza_deuda()
    {
        return $this->belongsTo(Deuda::class, 'PolizaDeuda', 'Id');
    }

    public function linea_credito()
    {
        return $this->belongsTo(SaldoMontos::class, 'LineaCredito', 'Id');
    }

    public function poliza_duda_tipo_cartera()
    {
        return $this->belongsTo(PolizaDeudaTipoCartera::class, 'PolizaDeudaTipoCartera', 'Id');
    }
}
