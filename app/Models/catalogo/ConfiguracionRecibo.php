<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionRecibo extends Model
{
    use HasFactory;
    protected $table = 'configuracion_recibos';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nota',
        'Pie',
        'Activo',
    ];
}
