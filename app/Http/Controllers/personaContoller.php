<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\persona;
use App\Models\cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuarioExport;
use Barryvdh\DomPDF\Facade as PDF ;

use Illuminate\Support\Facades\Auth;


class personaContoller extends Controller
{
            public function listar(Request $request){
                $buscar = $request->buscar;
                $res = persona::select("persona.*","cliente.*")
                        ->join("cliente", "cliente.id_persona", "=", "persona.id_persona")
                        ->where("persona.cedulaRuc", "like", "%".$buscar."%")
                        ->orWhere("persona.razonsocial_nombres", "like", "%".$buscar."%")
                        ->orWhere(DB::raw("CONCAT(persona.razonsocial_nombres, ' ', persona.razoncomercial_apellidos) like '%$buscar%'"))
                        ->orWhere("persona.contacto", "like", "%".$buscar."%")
                        ->orWhere("persona.direccion", "like", "%".$buscar."%")
                        ->orWhere("persona.eMail", "like", "%".$buscar."%")
                        ->orWhere("persona.ciudad", "like", "%".$buscar."%")
                        ->orWhere("persona.telefono", "like", "%".$buscar."%")->paginate(20);
                return [
                    'paginacion' => [
                        'total'        => $res->total(),
                        'current_page' => $res->currentPage(),
                        'per_page'     => $res->perPage(),
                        'last_page'    => $res->lastPage(),
                        'from'         => $res->firstItem(),
                        'to'           => $res->lastItem(),
                    ],
                    'datos' => $res
                ];
            }
    public function guardar(Request $request){
        $now = Carbon::now();

        $pr = new persona();
        $pr->cedulaRuc = $request->cedulaRuc;
        $pr->razonsocial_nombres = $request->razonsocial_nombres;
        $pr->tipo = $request->tipo;
        $pr->contacto  = $request->contacto;
        $pr->direccion = $request->direccion;
        $pr->direccion_entrega = $request->direccion_entrega;
        $pr->telefono = $request->telefono;
        $pr->celular = $request->celular;
        $pr->eMail = $request->eMail;
        $pr->ciudad = $request->ciudad;
        $pr->usuario_crea = 1;
        $pr->fecha_crea =$now;
        $pr->save();

        $id = $pr->id_persona;

        $cl = new cliente();
        $cl->categoria = $request->categoria;
        $cl->contacto1 = $request->contacto1;
        $cl->email1 = $request->email1;
        $cl->celular1 = $request->celular1;
        $cl->contacto2 = $request->contacto2;
        $cl->email2 = $request->email2;
        $cl->celular2 = $request->celular2;
        $cl->id_persona = $id;
        $cl->save();

    }
    public function editar(Request $request){
        $now = Carbon::now();
        $pr = persona::findOrFail($request->id_persona);
        $pr->cedulaRuc = $request->cedulaRuc;
        $pr->razonsocial_nombres = $request->razonsocial_nombres;
        $pr->tipo = $request->tipo;
        $pr->contacto  = $request->contacto;
        $pr->direccion = $request->direccion;
        $pr->direccion_entrega = $request->direccion_entrega;
        $pr->telefono = $request->telefono;
        $pr->celular = $request->celular;
        $pr->eMail = $request->eMail;
        $pr->ciudad = $request->ciudad;
        $pr->usuario_crea = 1;
        $pr->fecha_crea =$now;
        $pr->save();

        $cl = persona::findOrFail($request->id_persona);
        $cl->categoria = $request->categoria;
        $cl->contacto1 = $request->contacto1;
        $cl->email1 = $request->email1;
        $cl->celular1 = $request->celular1;
        $cl->contacto2 = $request->contacto2;
        $cl->email2 = $request->email2;
        $cl->celular2 = $request->celular2;
        $cl->save();
    }
    public function eliminar($id){
        persona::destroy($id);
    }
    public function excel()
    {
        return Excel::download(new personaExport, 'persona.xlsx');      
    }
    public function pdf()
    {
        $users = Usuario::get();
        $pdf = PDF::loadView('pdf.persona', compact('persona'));
        return $pdf->download('persona.pdf');      
    }

}
