<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPoliza extends Model
{
    use HasFactory;
    protected $table = 'tipo_poliza';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo'
    ];

    protected $guarded = [];

    public function tipo_poliza_has_aseguradora(){
        return $this->belongsToMany(Aseguradora::class, 'aseguradora_has_tipo_poliza', 'tipo_poliza_id', 'aseguradora_id');
    }

}
