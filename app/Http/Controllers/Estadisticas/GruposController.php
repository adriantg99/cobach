<?php

// ANA MOLINA 06/12/2023
namespace App\Http\Controllers\Estadisticas;
use App\Exports\Estadisticas\GruposExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;

// use Illuminate\Pagination\Paginator;

class GruposController extends Controller
{

    public function index()
    {
        return view('estadisticas.grupos.index');
    }
    public function excel_grupos($ciclo_id)
    {
        return Excel::download(new GruposExport($ciclo_id), 'sep911_grupos-'.date("Y-m-d H:i:s").'.xlsx');
    }
}
