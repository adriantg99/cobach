<?php

// ANA MOLINA 23/11/2023
namespace App\Http\Controllers\Estadisticas;
use App\Exports\Estadisticas\MatriculaExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;

// use Illuminate\Pagination\Paginator;

class MatriculaController extends Controller
{

    public function index()
    {
        return view('estadisticas.matricula.index');
    }
    public function excel_matricula($ciclo_id,$fecha)
    {
        //dd($fecha);
        return Excel::download(new MatriculaExport($ciclo_id,$fecha), 'sep911_matricula-'.date("Y-m-d H:i:s").'.xlsx');
    }
}
