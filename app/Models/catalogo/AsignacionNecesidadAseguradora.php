<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionNecesidadAseguradora extends Model    
{
    use HasFactory;

    protected $table = 'aseguradora_has_necesidad_proteccion';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'aseguradora_id',
        'necesidad_proteccion_id',
        'TipoPoliza',
        'Activo'
    ];

    public function tipo_polizas()
    {
        return $this->belongsTo(TipoPoliza::class, 'TipoPoliza', 'Id');
    }

    public function aseguradoras()
    {
        return $this->belongsTo(Aseguradora::class, 'aseguradora_id', 'Id');
    }

    public function necesidades()
    {
        return $this->belongsTo(NecesidadProteccion::class, 'necesidad_proteccion_id', 'Id');
    }
}
