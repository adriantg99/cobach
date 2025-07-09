<?php

namespace App\Http\Controllers\Estadisticas;

use App\Exports\Estadisticas\TableroExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class TableroController extends Controller
{

    public function index(){
        return view('estadisticas.tablero.index');

    }
    public function excel_tablero($ciclo,$plantel,  $periodo,   $grupo, $turno, $curso, $docente,  $vars,$chk1,$chk2,$chk3,$chkr,$chkf)
    {
         //dd($fecha);

        return Excel::download(new TableroExport( $ciclo,$plantel,  $periodo,   $grupo, $turno, $curso,$docente, $vars,$chk1,$chk2,$chk3,$chkr,$chkf), 'tablero-'.date("Y-m-d H:i:s").'.xlsx');
    }
}
