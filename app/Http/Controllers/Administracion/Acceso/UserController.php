<?php

namespace App\Http\Controllers\Administracion\Acceso;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('administracion.acceso.users.index');
    }

    public function agregar()
    {
        return view('administracion.acceso.users.agregar');
    }

    public function editar($user_id)
    {
        return view('administracion.acceso.users.editar',compact('user_id'));
    }

    public function eliminar($user_id)
    {
        if(Auth()->user()->hasPermissionTo('user-borrar'))
        {
            $user = User::find($user_id);
            $user->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'UserController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Elimino user id:'.$user_id,
            ]);

            return redirect()->route('user.index')->with('warning','Se eliminó correctamente el usuario id:'.$user_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'UserController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);
            return redirect()->route('user.index')->with('danger','No tiene los permisos necesarios');
        }
    }
}
