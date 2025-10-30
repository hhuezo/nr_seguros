<?php

namespace App\Models\polizas;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesempleoDetalle extends Model
{
    use HasFactory;
    protected $table = 'poliza_desempleo_detalle';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Id',
        'Desempleo',
        'FechaInicio',
        'FechaFinal',
        'MontoCartera',
        'Tasa',
        'PrimaCalculada',
        'Descuento',
        'PrimaDescontada',
        'ImpuestoBomberos',
        'GastosEmision',
        'Otros',
        'Iva',
        'TasaComision',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'ValorCCF',
        'APagar',
        'Comentario',
        'PrimaTotal',
        'DescuentoIva',
        'ImpresionRecibo',
        'EnvioCartera',
        'PagoAplicado',
        'SaldoA',
        'EnvioPago',
        'ExtraPrima',
        'ValorDescuento',
        'ExcelURL',
        'SubTotal',
        'Activo',
        'Referencia',
        'NumeroCorrelativo',
        'Anexo',
        'ComCartera',
        'ComPago',
        'ComAplicado',
        'NumeroRecibo',
        'Usuario',
        'FechaIngreso',

    ];

    public function usuarios(){
        return $this->belongsTo(User::class, 'Usuario', 'id');
    }

    public function poliza_desempleo(){
        return $this->belongsTo(Desempleo::class, 'Desempleo', 'id');
    }
}
