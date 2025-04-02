<?php

namespace App\Models\polizas;

use App\Models\polizas\Deuda;
use App\Models\polizas\PolizaDeudaCartera;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaExtraPrimados extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_extra_primado';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'NumeroReferencia',
        'PolizaDeuda',
        'Nombre',
        'FechaOtorgamiento',
        'MontoOtorgamiento',
        'Tarifa',
        'PorcentajeEP',
        'PagoEP',
        'Dui',
    ];

    public function poliza_deuda()
    {
        return $this->belongsTo(Deuda::class, 'PolizaDeuda', 'Id');
    }

    //PolizaDeudaExtraPrimados
    public function getPagoEP($id)
    {
        try {

            $extraprimado = PolizaDeudaExtraPrimados::findOrFail($id);

            $data_array = ["total" => 0, "saldo_capital" => 0, "prima_neta" => 0, "extra_prima" => 0, "interes" => 0];
            $registro = PolizaDeudaCartera::where('NumeroReferencia', $extraprimado->NumeroReferencia)
                ->where(function ($query) {
                    $query->where('PolizaDeudaDetalle', '=', 0)
                        ->orWhere('PolizaDeudaDetalle', '=', null);
                })->where('PolizaDeuda','=',$extraprimado->PolizaDeuda)->first();



            if ($registro) {
                //Saldos tradiciona, popular ,etc
                $total = $registro->TotalCredito;

                $data_array = [
                    "total" => $total, "saldo_capital" => $registro->SaldoCapital, "prima_neta" => $total * $registro->Tasa,
                    "extra_prima" => ($total * $registro->Tasa) * ($extraprimado->PorcentajeEP / 100), "interes" => $registro->Intereses
                ];
            }

            return $data_array;
        } catch (Exception $e) {
            $data_array = ["total" => 0, "saldo_capital" => 0, "prima_neta" => 0, "extra_prima" => 0, "interes" => 0];
            return $data_array;
        }
    }
}
