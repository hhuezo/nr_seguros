<?php

namespace App\Models\temp;

use App\Models\catalogo\SaldoMontos;
use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaExcluidos;
use App\Models\polizas\DeudaRequisitos;
use App\Models\polizas\PolizaDeudaCartera;
use App\Models\polizas\PolizaDeudaTipoCartera;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaTempCartera extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_temp_cartera';

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
        'MoraCapital',
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
        'PolizaDeudaTipoCartera',
        'NoValido',
        'EdadDesembloso',
        'FechaOtorgamientoDate',
        'Rehabilitado',
        'EdadRequisito',
        'MontoRequisito',
        'MontoMaximoIndividual',
        'Tasa',
        'Tarifa',
        'TipoDeuda',
        'PorcentajeExtraprima',
        'TipoDocumento',
        'SaldoInteresMora',
        'CarnetResidencia'
    ];


    public function linea_credito()
    {
        return $this->belongsTo(SaldoMontos::class, 'LineaCredito', 'Id');
    }

    public function poliza_deuda()
    {
        return $this->belongsTo(Deuda::class, 'PolizaDeuda', 'Id');
    }

    public function poliza_deuda_tipo_cartera()
    {
        return $this->belongsTo(PolizaDeudaTipoCartera::class, 'PolizaDeudaTipoCartera', 'Id');
    }


    public function calculoTodalSaldo()
    {
        try {
            $tipo_cartera = $this->LineaCredito;

            switch ($tipo_cartera) {
                case '1':
                    # saldo a capital
                    $saldo = $this->SaldoCapital;
                    break;
                case '2':
                    # saldo a capital mas intereses
                    $saldo =  $this->SaldoCapital + $this->Intereses;
                    break;
                case '3':
                    # saldo a capital mas intereses mas covid
                    $saldo = $this->SaldoCapital + $this->Intereses +  $this->InteresesCovid;
                    break;
                case '4':
                    # saldo a capital as intereses mas covid mas moratorios
                    $saldo = $this->SaldoCapital + $this->Intereses +  $this->InteresesCovid +  $this->InteresesMoratorios;
                    break;
                case '5':
                    # .monto moninal
                    $saldo = $this->MontoOtorgado;
                    break;
                case '6':
                    //se cambio por intereses
                    # .monto otorgado
                    $saldo = $this->Intereses;
                    break;
                default:
                    # .sando capital
                    //$saldo = $this->SaldoCapital;

                    $saldo =  0.00;
                    break;
            }

            return $saldo;
        } catch (Exception $e) {
            return 0.00;
        }
    }


    public function getNumerosReferencia($tipoCartera)
    {

        $data = PolizaDeudaTempCartera::where('Dui', $this->Dui)
            ->where('PolizaDeudaTipoCartera', $tipoCartera)
            ->where('Pasaporte', $this->Pasaporte)
            ->where('CarnetResidencia', $this->CarnetResidencia)
            ->where('PolizaDeuda', $this->PolizaDeuda)
            ->orderBy('FechaOtorgamientoDate')
            ->get();



        $concatenatedReferences = '';


        $deuda = Deuda::findOrFail($this->PolizaDeuda);

        $acumulado = 0;
        foreach ($data as $item) {
            $acumulado += $item->TotalCredito ?? 0.00;
            $requisito = $deuda->requisitos->where('EdadInicial', '<=', $item->EdadDesembloso)->where('EdadFinal', '>=', $item->EdadDesembloso)
                ->where('MontoInicial', '<=', $acumulado)->where('MontoFinal', '>=', $acumulado)->first();



            if ($requisito && ($requisito->perfil->PagoAutomatico == 1 || $requisito->perfil->DeclaracionJurada == 1)) {
                $style = '<span style="color: black;">' . $item->NumeroReferencia . '</span>';
            } else {
                $style = '<span style="color: red;">' . $item->NumeroReferencia . '</span>';
            }

            $concatenatedReferences .= $style . ', ';
        }

        // Elimina la última coma y espacio
        $concatenatedReferences = rtrim($concatenatedReferences, ', ');

        // Retorna el resultado
        return $concatenatedReferences;
    }

    public function creditoRehabilitado($numeroReferencia, $axoAnterior, $mesAnterior)
    {
        try {
            $count = PolizaDeudaCartera::where('NumeroReferencia', $numeroReferencia)
                ->where(function ($query) use ($axoAnterior, $mesAnterior) {
                    $query->where('Axo', '<>', $axoAnterior)
                        ->orWhere('Mes', '<>', $mesAnterior);
                })
                ->count();
            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function excluidoEdad()
    {
        $excluido = DeudaExcluidos::where('NumeroReferencia', $this->NumeroReferencia)->where('EdadMaxima', '<>', null)->count();

        return $excluido;
    }

    public function excluidoResponsabilidad()
    {
        $excluido = DeudaExcluidos::where('NumeroReferencia', $this->NumeroReferencia)->where('ResponsabilidadMaxima', '<>', null)->count();

        return $excluido;
    }
}
