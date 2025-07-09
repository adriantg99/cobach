<?php
// ANA MOLINA 17/02/2024
namespace App\Http\Controllers\Estadisticas;
use App\Exports\Estadisticas\Calificaciones911Export;
use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;

// use Illuminate\Pagination\Paginator;

class CalificacionesController extends Controller
{

    public function index()
    {
        return view('estadisticas.calificaciones.index');
    }
    public function excel_calificaciones($ciclo_id)
    {
        //dd($fecha);
        return Excel::download(new Calificaciones911Export($ciclo_id), 'sep911_calificaciones_fin_'.date("Y-m-d H:i:s").'.xlsx');
    }
}
