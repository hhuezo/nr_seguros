<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NecesidadProteccion extends Model
{
    use HasFactory;
    protected $table = 'necesidad_proteccion';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo',
        'TipoPoliza'
    ];

    protected $guarded = [];

    public function necesidad_has_aseguradora(){
        return $this->belongsToMany(Aseguradora::class, 'aseguradora_has_necesidad_proteccion', 'necesidad_proteccion_id', 'aseguradora_id');
    }

    public function tipo_poliza()
    {
        return $this->belongsTo(TipoPoliza::class, 'TipoPoliza', 'Id');
    }

}
