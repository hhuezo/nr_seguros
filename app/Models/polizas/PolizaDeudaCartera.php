<?php

namespace App\Models\polizas;

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

    public function linia_credito()
    {
        return $this->belongsTo(DeudaCredito::class, 'LineaCredito', 'Id');
    }
}
