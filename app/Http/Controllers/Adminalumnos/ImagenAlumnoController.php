<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;

class ImagenAlumnoController extends Controller
{
    //Inicial para subir imagenes a alumnos
    //permiso alumno-imagen
    public function index()
    {
        if(Auth()->user()->can('alumno-imagen'))
        {
            return view('adminalumnos.imagen.imagen_alumno');
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'ImagenAlumnoController',
                //'component'     =>  'FormComponent',
                'function'  =>  'index',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.planteles.index')->with('danger','No tiene los permisos necesarios');
        }
    }
}
