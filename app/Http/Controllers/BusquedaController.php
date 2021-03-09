<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Servicio;
use App\Models\ServicioTrabajador;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function buscarServicios(Request $request){

        $personas=Persona::select('personas.*')
            ->join('trabajadores','personas.id','=','trabajadores.persona_id')
            ->join('servicio_trabajador','servicio_trabajador.trabajador_id','=','trabajadores.id')
            ->join('servicios','servicio_trabajador.servicio_id','=','servicios.id')
            ->where('servicios.nombre',$request['servicio'])
            ->where('trabajadores.habilitado','a')
            ->where('personas.direccion',$request['direccion']);

            if($request['turno']=='mañana'){
               $personas=$personas->whereBetween('servicio_trabajador.hora_inicio',['00:00:00','11:59:59']);
            }else{
                $personas=$personas->whereBetween('servicio_trabajador.hora_fin',['12:00:00','23:59:59']);
            }
            //->whereBetween($request['hora'],['servicio_trabajador.hora_inicio','servicio_trabajador.hora_fin']);
            $personas=$personas->distinct()
            ->get();


            return $personas;

    }
    public function seviciosLugares(){
        $servicios=Servicio::select('nombre')->distinct()->get();
        $lugares=Persona::select('direccion')->distinct()->get();
        return response()->json(['servicios'=>$servicios,'lugares'=>$lugares]);

    }
}
