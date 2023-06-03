<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaUsuario extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_usuario';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Vida',
        'NumeroUsuario',
        'SumaAsegurada',
        'SubTotalAsegurado',
        'Tasa',
        'TotalAsegurado' 
    ];

    protected $guarded = [];

    public function vidas(){
        return $this->belongsTo('App\Models\polizas\Vida', 'Vida', 'Id');
    }
}
