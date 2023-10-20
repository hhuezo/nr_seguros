<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;
    protected $table = 'poliza_comentarios';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Comentario',
        'Activo',
        'Usuario',
        'FechaIngreso',
        'Poliza',
        'DetallePoliza',
    ];

    public function residencia(){
        return $this->belongsTo(Residencia::class, 'Residencia', 'Id');
    }

    public function deuda(){
        return $this->belongsTo(Deuda::class, 'Deuda', 'Id');
    }

}
