<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'cliente';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nit',
        'Dui',
        'Nombre',
        'DireccionRecidencia',
        'DireccionCorrespondencia',
        'TelefonoResidencia',
        'TelefonoOficina',
        'TelefonoCelular',
        'Correo',
        'Ruta',
        'ResponsablePago',
        'TipoContribuyente',
        'UbicacionCobro',
        'Contacto',
        'Referencia',
        'NumeroTarjeta',
        'FechaVencimiento',
        'Genero',
        'TipoPersona',
        'Activo',
        'FechaCreacion',
        'UsuarioCreacion'
    ];

    protected $guarded = [];

    public function ruta()
    {
        return $this->belongsTo('App\Models\catalogo\Ruta', 'Ruta', 'Id');
    }

    public function tipo_contribuyente()
    {
        return $this->belongsTo('App\Models\catalogo\TipoContribuyente', 'TipoContribuyente', 'Id');
    }

    public function ubicacion_cobro()
    {
        return $this->belongsTo('App\Models\catalogo\UbicacionCobro', 'UbicacionCobro', 'Id');
    }
}