<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioDocumento extends Model
{
    use HasFactory;
    protected $table = 'negocio_documentos';

    protected $primaryKey = 'Id';


    protected $fillable = [
        'Negocio',
        'Nombre',
        'NombreOriginal',
        'Activo',
    ];
    public $timestamps = false;

    public function negocio()
    {
        return $this->belongsTo(Negocio::class, 'Negocio', 'Id');
    }
}
