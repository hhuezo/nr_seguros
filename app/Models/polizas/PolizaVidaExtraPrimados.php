<?php

namespace App\Models\polizas;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaVidaExtraPrimados extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_extra_primado';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'NumeroReferencia',
        'PolizaVida',
        'Nombre',
        'FechaOtorgamiento',
        'MontoOtorgamiento',
        'Tarifa',
        'PorcentajeEP',
        'PagoEP',
        'Dui',
    ];

    public function poliza_vida()
    {
        return $this->belongsTo(Vida::class, 'PolizaVida', 'Id');
    }

    //PolizaDeudaExtraPrimados
    public function getPagoEP($id)
    {
        try {

            $extraprimado = PolizaVidaExtraPrimados::findOrFail($id);

            $data_array = ["total" => 0, "saldo_capital" => 0, "prima_neta" => 0, "extra_prima" => 0, "interes" => 0];
            $registro = VidaCartera::where('NumeroReferencia', $extraprimado->NumeroReferencia)
                ->where(function ($query) {
                    $query->where('PolizaVidaDetalle', '=', 0)
                        ->orWhere('PolizaVidaDetalle', '=', null);
                })->where('PolizaVida','=',$extraprimado->PolizaVida)->first();

            //dd($registro);

            if ($registro) {
                //Saldos tradiciona, popular ,etc
                $total = $registro->SumaAsegurada;

                $data_array = [
                    "SumaAsegurada" => $registro->SumaAsegurada,  "PrimaNeta" => $total * $registro->Tasa,
                    "ExtraPrima" => ($total * $registro->Tasa) * ($extraprimado->PorcentajeEP / 100)];

                //dd($data_array);
            }

            return $data_array;
        } catch (Exception $e) {
            $data_array = ["total" => 0, "saldo_capital" => 0, "prima_neta" => 0, "extra_prima" => 0, "interes" => 0];
            return $data_array;
        }
    }
}
