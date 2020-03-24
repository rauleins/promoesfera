<?php

namespace App\Exports;

use App\Models\persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite \ Excel \ Concerns \ shouldAutoSize; 

class personaExport implements FromCollection, WithHeadings, ShouldAutoSize 
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return persona::select(
            'id_persona',
            'cedulaRuc',
            'razonsocial_nombres',
            'tipo',
            'contacto',
            'direccion',
            'direccion_entrega',
            'telefono',
            'celular',
            'eMail',
            'ciudad',
            'Usuario_crea',
            'fecha_modifica',
            'usuario_crea',
            'usuario_modifica'
        )
            ->get();
    }
    public function headings(): array
    {
        return [
            'No.',
            'Cédula',
            'Nombre Apellido',
            'tipo',
            'Contacto',
            'Dirección',
            'Dirección Entrega',
            'Teléfono',
            'Celular',
            'Email',
            'Ciudad',
            'Fecha de Crea',
            'Fecha de Modifica',
            'Usuario Crea',
            'Usuario Modifica',

        ];
    }
}
