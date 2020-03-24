<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cliente extends Model
{
    protected $table='cliente';
    protected $primaryKey='id_cliente';
    protected $fillable = ['categoria', 'contacto1', 'email1', 'celular1', 'contacto2', 'email2', 'celular2', 'id_persona' ];
    public $timestamps = false;
}
