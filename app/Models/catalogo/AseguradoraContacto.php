<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AseguradoraContacto extends Model
{
    use HasFactory;
    protected $table = 'aseguradora_contacto';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Cargo',
        'Telefono',
        'Email',
        'Aseguradora'
    ];

    public function cargo()
    {
        return $this->belongsTo(AseguradoraCargo::class, 'Cargo', 'Id');
    }
}
