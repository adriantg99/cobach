<?php

namespace App\Http\Controllers\Estadisticas;

use App\Exports\Estadisticas\ExplorarExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ExplorarController extends Controller
{

    public function index(){
        return view('estadisticas.explorar.index');

    }
    public function excel_explorar($ciclo,$plantel,  $periodo,   $grupo,$turno, $curso,$docente)
    {
         //dd($fecha);
        return Excel::download(new ExplorarExport( $ciclo,$plantel,  $periodo,   $grupo,$turno,$curso, $docente), 'explorar-'.date("Y-m-d H:i:s").'.xlsx');
    }
}
