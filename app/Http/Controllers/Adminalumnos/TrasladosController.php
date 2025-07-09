<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Imports\Controlesc\TrasladosImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TrasladosController extends Controller
{
    public function index()
    {
        dd('ddd');
        //Excel::import(new TrasladosImport, 'prueba.xlsx');
    }

    public function ingresos()
    {
        return view('adminalumnos.traslados_alumnos.traslado');
    }
}
