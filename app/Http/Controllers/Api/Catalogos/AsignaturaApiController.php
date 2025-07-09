<?php

namespace App\Http\Controllers\Api\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\AsignaturaModel;
use Illuminate\Http\Request;

class AsignaturaApiController extends Controller
{
    public function buscar_asignatura(){

    }

    public function cambioClave($clave_asignatura){
        
        $asignatura = AsignaturaModel::where('id', $clave_asignatura)->first();

        if ($asignatura) {
            return response()->json($asignatura);
        } else {
            return response()->json(['error' => 'No se encontró ninguna asignatura con esa clave'], 404);
        }

    }
}
