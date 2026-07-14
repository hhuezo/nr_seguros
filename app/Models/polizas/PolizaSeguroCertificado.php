<?php

namespace App\Models\polizas;

use App\Models\catalogo\EstadoCertificado;
use App\Models\catalogo\MotivoCancelacion;
use App\Models\catalogo\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCertificado extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_certificados';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroId',
        'Plan',
        'NumeroCertificado',
        'CertificadoAseguradora',
        'CodAsegurado',
        'Asegurado',
        'FechaNacimiento',
        'Sexo',
        'VigenciaDesde',
        'VigenciaHasta',
        'FechaInclusion',
        'DiasVigencia',
        'ValorAsegurado',
        'PrimaTotal',
        'PorcentajeDescuentoRentabilidad',
        'ValorDescuento',
        'PorcentajeDescuentoBuenaExperiencia',
        'ValorDescuentoBuenaExperiencia',
        'PorcentajeOtrosDescuentos',
        'ValorOtrosDescuentos',
        'PrimaNeta',
        'PrimaExenta',
        'GastosEmision',
        'GastosFraccionamiento',
        'GastosBomberos',
        'OtrosGastos',
        'Impuestos',
        'TotalCertificado',
        'Estado',
        'EstadoCertificado',
        'MotivoCancelacion',
        'MotivoExclusion',
        'FechaExclusion',
        'UsuarioModifica',
        'FechaModificacion',
        'DatosJson',
        'Observacion',
        'Activo',
    ];

    public function poliza()
    {
        return $this->belongsTo(PolizaSeguro::class, 'PolizaSeguroId', 'Id');
    }

    public function dependientes()
    {
        return $this->hasMany(PolizaSeguroCertificadoDependiente::class, 'PolizaSeguroCertificadoId', 'Id')
            ->where('Activo', 1)
            ->orderBy('NumeroDependiente', 'asc');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'Plan', 'Id');
    }

    public function coberturasCertificado()
    {
        return $this->hasMany(PolizaSeguroCertificadoCobertura::class, 'PolizaSeguroCertificadoId', 'Id')
            ->where('Activo', 1)
            ->orderBy('Id', 'asc');
    }

    public function datosTecnicosCertificado()
    {
        return $this->hasMany(PolizaSeguroCertificadoDatoTecnico::class, 'PolizaSeguroCertificadoId', 'Id')
            ->where('Activo', 1)
            ->orderBy('Id', 'asc');
    }

    public function beneficiarios()
    {
        return $this->hasMany(PolizaSeguroBeneficiario::class, 'PolizaSeguroCertificadoId', 'Id')
            ->where('Activo', 1)
            ->orderBy('Id', 'asc');
    }

    public function beneficiariosTodos()
    {
        return $this->hasMany(PolizaSeguroBeneficiario::class, 'PolizaSeguroCertificadoId', 'Id')
            ->orderBy('Id', 'asc');
    }

    public function cesionBeneficios()
    {
        return $this->hasMany(PolizaSeguroCesionBeneficio::class, 'PolizaSeguroCertificadoId', 'Id')
            ->where('Activo', 1)
            ->orderBy('Id', 'asc');
    }

    public function cesionBeneficiosTodos()
    {
        return $this->hasMany(PolizaSeguroCesionBeneficio::class, 'PolizaSeguroCertificadoId', 'Id')
            ->orderBy('Id', 'asc');
    }

    public function estadoCertificado()
    {
        return $this->belongsTo(EstadoCertificado::class, 'EstadoCertificado', 'Id');
    }

    public function motivoCancelacion()
    {
        return $this->belongsTo(MotivoCancelacion::class, 'MotivoCancelacion', 'Id');
    }

    public function usuarioModifica()
    {
        return $this->belongsTo(User::class, 'UsuarioModifica', 'id');
    }
}
