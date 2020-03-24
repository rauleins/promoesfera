<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class proveedor extends Model
{
    protected $table='proveedor';
    protected $primaryKey='id_proveedor';
    protected $fillable = ['linkFacturaE', 'usuarioFacturaE', 'claveFacturaE', 'productoOferta', 'contacto1', 'celular1', 'email1', 'id_persona'];
    public $timestamps = false;
}
