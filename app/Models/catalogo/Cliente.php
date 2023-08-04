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
        'RegistroFiscal',
        'FechaNacimiento',
        'EstadoFamiliar',
        'NumeroDependientes',
        'Ocupacion',
        'DireccionResidencia',
        'DireccionCorrespondencia',
        'TelefonoResidencia',
        'TelefonoOficina',
        'TelefonoCelular',
        'CorreoPrincipal',
        'CorreoSecundario',
        'FechaVinculacion',
        'FechaBaja',
        'ResponsablePago',
        'UbicacionCobro',
        'FormaPago',
        'Estado',
        'TipoPersona',
        'Genero',
        'TipoContribuyente',
        'Referencia',
        'FechaIngreso',
        'UsuarioIngreso',
        'Facebook',
        'ActividadesCreativas',
        'EstiloVida',
        'SitioWeb',
        'NecesidadProteccion',
        'Laptop',
        'PC',
        'Tablet',
        'SmartWatch',
        'DispositivosOtros',
        'Informarse',
        'Instagram',
        'TieneMascota',
        'MotivoEleccion',
        'PreferenciaCompra',
        'Efectivo',
        'TarjetaCredito',
        'App',
        'MonederoEletronico',
        'CompraOtros',
        'Activo',
        'Informacion',     
        'Distrito'
    ];

    protected $guarded = [];

    /*public function ruta()
    {
        return $this->belongsTo('App\Models\catalogo\Ruta', 'Ruta', 'Id');
    }*/

    public function tipo_contribuyente()
    {
        return $this->belongsTo('App\Models\catalogo\TipoContribuyente', 'TipoContribuyente', 'Id');
    }

    public function ubicacion_cobro()
    {
        return $this->belongsTo('App\Models\catalogo\UbicacionCobro', 'UbicacionCobro', 'Id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'Distrito', 'Id');
    }

    public function estado()
    {
        return $this->belongsTo(ClienteEstado::class, 'Estado', 'Id');
    }
}
