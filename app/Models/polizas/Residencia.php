<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residencia extends Model
{
    use HasFactory;
    protected $table = 'poliza_residencia';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Numero',
        'NumeroPoliza',
        'Codigo',
        'Aseguradora',
        'Asegurado',
        'EstadoPoliza',
        'VigenciaDesde',
        'VigenciaHasta',
        'LimiteGrupo',
        'LimiteIndividual',
        'MontoCartera',
        'Cliente',
        'Tasa',
        'Prima',
        'Vendedor',
        'Descuento',
        'GastosEmision',
        'ImpuestoBomberos',
        'Iva',
        'ValorCCF',
        'APagar',
        'ComentariosDeCobro',
        'DescuentoIva',
        'Nit',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'Activo',
    ];

    protected $guarded = [];

    public function clientes(){
        return $this->belongsTo('App\Models\catalogo\Cliente', 'Asegurado', 'Id');
    }

}
