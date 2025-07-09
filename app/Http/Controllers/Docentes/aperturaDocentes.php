<?php

namespace App\Http\Controllers\Docentes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class aperturaDocentes extends Controller
{
    //
    public function index()
    {
        return view('docentes.apertura');
    }

    public function replicacion()
    {
        return view('cursos.replicacion');
    }
}
