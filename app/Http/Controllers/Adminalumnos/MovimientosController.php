<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovimientosController extends Controller {

    public function index( Request $request ): View {
        $alumno_id = $request->route( 'alumno_id' );
        return view( 'adminalumnos.movimientos.movimientos-index', compact('alumno_id') );
    }

}