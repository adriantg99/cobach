<?php
// ANA MOLINA 27/06/2023
namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AreaFormacionModel;
use Illuminate\Http\Request;

class AreaFormacionController extends Controller
{
    public function index()
    {
        return view('catalogos.areasformacion.index');
    }

    public function agregar()
    {

        return view('catalogos.areasformacion.agregar');
    }

    public function editar($areaformacion_id)
    {
        return view('catalogos.areasformacion.editar', compact('areaformacion_id'));
    }

    public function eliminar($areaformacion_id)
    {
        if(Auth()->user()->hasPermissionTo('areaformacion-borrar'))
        {
            $areaformacion = AreaFormacionModel::find($areaformacion_id);
            $areaformacion->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AreaFormacionController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó área de formación id:'.$areaformacion_id,
            ]);

            return redirect()->route('catalogos.areasformacion.index')->with('warning','Se eliminó correctamente el área de formación id:'.$areaformacion_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AreaFormacionController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.areasformacion.index')->with('danger','No tiene los permisos necesarios');
        }
    }
}
