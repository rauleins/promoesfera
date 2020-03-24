<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class persona extends Model
{
    protected $table='persona';
    protected $primaryKey='id_persona';
    protected $fillable = ['cedulaRuc', 'razonsocial_nombres', 'razoncomercial_apellidos', 'tipo', 'contacto', 'direccion', 'direccion_entrega', 'telefono', 'usuario_crea', 'celular', 'eMail', 'ciudad', 'fecha_crea', 'fecha_modifica', 'usuario_modifica'];
    public $timestamps = false;
}
