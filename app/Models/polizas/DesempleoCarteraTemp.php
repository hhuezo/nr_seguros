<?php

namespace App\Models\polizas;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesempleoCarteraTemp extends Model
{
    use HasFactory;

    protected $table = 'poliza_desempleo_cartera_temp';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'PolizaDesempleo',
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
        'MoraCapital',
        'InteresesMoratorios',
        'SaldoTotal',
        'User',
        'Axo',
        'Mes',
        'FechaInicio',
        'FechaFinal',
        'TipoError',
        'FechaNacimientoDate',
        'Edad',
        'InteresesCovid',
        'MontoNominal',
        'NoValido',
        'EdadDesembloso',
        'FechaOtorgamientoDate',
        'Excluido',
        'Rehabilitado',
        'EdadRequisito',
    ];

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

}
