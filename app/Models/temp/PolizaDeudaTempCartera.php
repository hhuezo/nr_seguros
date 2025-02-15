<?php

namespace App\Models\temp;

use App\Models\polizas\Deuda;
use App\Models\polizas\DeudaCredito;
use App\Models\polizas\DeudaExcluidos;
use App\Models\polizas\PolizaDeudaCartera;
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
        'EdadDesembloso',
        'FechaOtorgamientoDate',
        'Rehabilitado',
        'EdadRequisito',
        'MontoRequisito',
        'MontoMaximoIndividual'
    ];


    public function linea_credito()
    {
        return $this->belongsTo(DeudaCredito::class, 'LineaCredito', 'Id');
    }

    public function poliza_deuda()
    {
        return $this->belongsTo(Deuda::class, 'PolizaDeuda', 'Id');
    }


    public function calculoTodalSaldo()
    {

        try {
            $tipo_cartera = $this->linea_credito->Saldos;
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
                    $saldo = $this->MontoNominal;
                    break;
                case '6':
                    # .monto otorgado
                    $saldo = $this->MontoOtorgado;
                    break;
                default:
                    # .sando capital
                    $saldo = $this->SaldoCapital;
                    break;
            }

            return $saldo;
        } catch (Exception $e) {
            return 0.00;
        }
    }


    public function getNumerosReferencia()
    {
        $data = PolizaDeudaTempCartera::where('Dui', $this->Dui)
            ->where('PolizaDeuda', $this->PolizaDeuda)
            ->orderBy('FechaOtorgamientoDate')
            ->get();

        $concatenatedReferences = '';

        foreach ($data as $obj) {
            $isSingleRecord = $data->count() === 1;

            $style = ($isSingleRecord || is_null($obj->MontoRequisito) || is_null($obj->EdadRequisito))
                ? '<span style="color: black;">' . $obj->NumeroReferencia . '</span>'
                : '<span style="color: red;">' . $obj->NumeroReferencia . '</span>';

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
