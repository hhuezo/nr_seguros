<?php

namespace App\Models\catalogo;

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
        'Activo'
    ];

    protected $guarded = [];

    public function aseguradoras()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }


}
