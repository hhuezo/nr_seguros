<?php

namespace App\Models\catalogo;

use App\Models\polizas\DeudaRequisitos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;
    protected $table = 'perfiles';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Descripcion',
        'Aseguradora',
        'PagoAutomatico',
        'DeclaracionJurada',
        'Activo'
    ];

    protected $guarded = [];

    public function aseguradoras()
    {
        return $this->belongsTo(Aseguradora::class, 'Aseguradora', 'Id');
    }


    public function requisitos()
    {
        return $this->hasMany(DeudaRequisitos::class,'Perfil', 'Id');
    }
}
