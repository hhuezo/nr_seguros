<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Deuda extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Numero',
        'NumeroPoliza',
        'Nit',
        'Codigo',
        'Asegurado',
        'Aseguradora',
        'Ejecutivo',
        'VigenciaDesde',
        'VigenciaHasta',
        'Tasa',
        'Beneficios',
        'ClausulasEspeciales',
        'Concepto',
        'EstadoPoliza',
        'Descuento',
        'Comision',
        'FechaIngreso',
        'Activo',
        'Bomberos',
        'Videuda',
        'LimiteMaximo'
    ];

    protected $guarded = [];

    public function clientes()
    {
        return $this->belongsTo('App\Models\catalogo\Cliente', 'Asegurado', 'Id');
    }

    public function aseguradoras()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }

    public function ejecutivos()
    {
        return $this->belongsTo('App\Models\catalogo\Ejecutivo', 'Ejecutivo', 'Id');
    }
    public function estadoPolizas()
    {
        return $this->belongsTo('App\Models\catalogo\EstadoPoliza', 'EstadoPoliza', 'Id');
    }

    public function planes()
    {
        return $this->belongsTo('App\Models\catalogo\Plan', 'Plan', 'Id');
    }

    public function requisitos()
    {
        return $this->hasMany(DeudaRequisitos::class, 'Deuda', 'Id');
    }

    public function extra_primados()
    {
        return $this->hasMany(PolizaDeudaExtraPrimados::class, 'PolizaDeuda', 'Id');
    }

    public function conteoEdadMaxima()
    {
        $count_edad_maxima =  DB::table('poliza_deuda_temp_cartera as temp')
            ->leftJoin('poliza_deuda_excluidos as excluidos', function ($join) {
                $join->on('temp.NumeroReferencia', '=', 'excluidos.NumeroReferencia')
                    ->where('excluidos.EdadMaxima', '=', 1);
            })
            ->whereNull('excluidos.NumeroReferencia')
            ->where('temp.PolizaDeuda', $this->Id)
            ->where('temp.User', auth()->user()->id)
            ->where('temp.EdadDesembloso', '>', $this->EdadMaximaTerminacion)
            ->count();


        $count_responsabilidad_maxima =  DB::table('poliza_deuda_temp_cartera as temp')
            ->leftJoin('poliza_deuda_excluidos as excluidos', function ($join) {
                $join->on('temp.NumeroReferencia', '=', 'excluidos.NumeroReferencia')
                    ->where('excluidos.ResponsabilidadMaxima', '=', 1);
            })
            ->whereNull('excluidos.NumeroReferencia')
            ->where('temp.PolizaDeuda', $this->Id)
            ->where('temp.User', auth()->user()->id)
            ->where('temp.TotalCredito', '>', $this->ResponsabilidadMaxima)
            ->count();

        $total = $count_edad_maxima + $count_responsabilidad_maxima;

        return $total;
    }


    public function deuda_tipos_cartera()
    {
        return $this->hasMany(PolizaDeudaTipoCartera::class, 'PolizaDeuda', 'Id');
    }

    public function renovaciones()
    {
        return $this->hasMany(PolizaDeudaHistorica::class, 'Deuda', 'Id');
    }

    public function vidas(){
        return $this->belongsTo(Vida::class,'PolizaVida');
    }

    public function desempleos(){
        return $this->belongsTo(Desempleo::class, 'PolizaDesempleo');
    }


}
