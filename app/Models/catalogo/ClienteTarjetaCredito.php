<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteTarjetaCredito extends Model
{
    use HasFactory;
    protected $table = 'cliente_tarjeta_credito';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Cliente',
        'NumeroTarjeta',
        'FechaVencimiento',
        'PolizaVinculada',
        'MetodoPago'
    ];

    protected $guarded = [];

    public function metodo_pago()
    {
        return $this->belongsTo(ClienteMetodoPago::class, 'MetodoPago', 'Id');
    }
}
