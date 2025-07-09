<?php
// ANA MOLINA 28/06/2023
namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Exports\Catalogos\AsignaturaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class AsignaturaController extends Controller
{
    public function index()
    {
        return view('catalogos.asignaturas.index');
    }

    public function agregar()
    {
        return view('catalogos.asignaturas.agregar');
    }

    public function editar($asignatura_id)
    {
        return view('catalogos.asignaturas.editar', compact('asignatura_id'));
    }

    public function exportar()
    {
        return Excel::download(new AsignaturaExport, 'sce-cobach-asignatura-'.date("Y-m-d H:i:s").'.xlsx');
    }

    public function eliminar($asignatura_id)
    {
        if(Auth()->user()->hasPermissionTo('asignatura-borrar'))
        {
            $asignatura = AsignaturaModel::find($asignatura_id);
            $asignatura->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AsignaturaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó asignatura id:'.$asignatura_id,
            ]);

            return redirect()->route('catalogos.asignaturas.index')->with('warning','Se eliminó correctamente el asignatura id:'.$asignatura_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AsignaturaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.asignaturas.index')->with('danger','No tiene los permisos necesarios');
        }
    }
}
