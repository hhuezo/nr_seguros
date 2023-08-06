<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aseguradora extends Model
{
    use HasFactory;
    protected $table = 'aseguradora';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo',
        'Codigo',
        'Telefono',
        'Contacto',
        'Direccion',
        'PaginaWeb',
        'Fax',
        'Nit',
        'RegistroFiscal',
        'Abreviatura',
        'Correo'
    ];

    protected $guarded = [];


    public function aseguradora_has_necesidad(){
        return $this->belongsToMany(NecesidadProteccion::class, 'aseguradora_has_necesidad_proteccion', 'aseguradora_id', 'necesidad_proteccion_id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'Distrito', 'Id');
    }

    
}
