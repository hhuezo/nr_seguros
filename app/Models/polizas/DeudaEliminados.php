<?php

namespace App\Models\polizas;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaEliminados extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_eliminados';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Dui',
        'Nombre',
        'NumeroReferencia',
        'Poliza',
        'Mes',
        'Usuario'
    ];


    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'Poliza', 'Id');
    }
    public function usuarios()
    {
        return $this->belongsTo(User::class, 'Usuario', 'id');
    }
}
