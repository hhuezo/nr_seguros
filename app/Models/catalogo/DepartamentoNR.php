<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentoNR extends Model
{
    use HasFactory;
    protected $table = 'departamento_nr'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'Id'; // Columna de la clave primaria

    public $timestamps = false; // Desactivar las columnas 'created_at' y 'updated_at'

    protected $fillable = [
        'Nombre',
        'Activo'
    ]; // Columnas que se pueden llenar de manera masiva

}
