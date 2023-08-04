<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    protected $table = 'distrito';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre', 
        'Municipio'       
    ];

    protected $guarded = [];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'Municipio', 'Id');
    }
}
