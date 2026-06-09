<?php

namespace App\Models\polizas;

use App\Models\catalogo\Parentesco;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroBeneficiario extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_beneficiarios';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroId',
        'Nombre',
        'Parentesco',
        'FechaNacimiento',
        'Porcentaje',
        'Activo',
    ];

    public function poliza()
    {
        return $this->belongsTo(PolizaSeguro::class, 'PolizaSeguroId', 'Id');
    }

    public function parentesco()
    {
        return $this->belongsTo(Parentesco::class, 'Parentesco', 'Id');
    }
}
