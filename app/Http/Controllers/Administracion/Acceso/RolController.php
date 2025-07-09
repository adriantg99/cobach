<?php

namespace App\Http\Controllers\Administracion\Acceso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\BitacoraModel;

class RolController extends Controller
{
    public function index()
    {
        return view('administracion.acceso.roles.index');
    }

    public function agregar()
    {
        return view('administracion.acceso.roles.agregar');
    }

    public function editar($rol_id)
    {
        return view('administracion.acceso.roles.editar', compact('rol_id'));
    }

    public function eliminar($rol_id)
    {
        if(Auth()->user()->hasPermissionTo('rol-borrar'))
        {
            DB::table("roles")->where('id',$rol_id)->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'RolController',
                //'component'     =>  'FormComponent',
                'function'  =>  'guardar',
                'description'   =>  'Elimino rol id:'.$rol_id,
            ]);

            return redirect()->route('rol.index')->with('warning','Se eliminó correctamente el usuario id:'.$rol_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'RolController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);
            return redirect()->route('rol.index')->with('danger','No tiene los permisos necesarios');
        }

    }
}
