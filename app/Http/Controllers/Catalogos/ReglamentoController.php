<?php
// ANA MOLINA 02/08/2023
namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\ReglamentoModel;
use Illuminate\Http\Request;

class ReglamentoController extends Controller
{
    public function index()
    {
        return view('catalogos.reglamentos.index');
    }

    public function agregar()
    {

        return view('catalogos.reglamentos.agregar');
    }

    public function editar($reglamento_id)
    {
        return view('catalogos.reglamentos.editar', compact('reglamento_id'));
    }

    public function eliminar($reglamento_id)
    {
        if(Auth()->user()->hasPermissionTo('reglamento-borrar'))
        {
            $reglamento = ReglamentoModel::find($reglamento_id);
            $reglamento->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'ReglamentoController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó reglamento id:'.$reglamento_id,
            ]);

            return redirect()->route('catalogos.reglamentos.index')->with('warning','Se eliminó correctamente el reglamento id:'.$reglamento_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'ReglamentoController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.reglamentos.index')->with('danger','No tiene los permisos necesarios');
        }
    }
}
