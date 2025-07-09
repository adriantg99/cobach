<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class promocionAlumnos extends Controller
{
    public function index()
    {
      
        return view('adminalumnos.promocion.index');
        
    }
}
