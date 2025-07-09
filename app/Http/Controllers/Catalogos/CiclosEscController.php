<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CiclosEscController extends Controller
{
    public function index()
    {
        Paginator::useBootstrap();
        $ciclos_esc = CicloEscModel::orderBy('per_inicio', 'desc')->get();
        $count_ciclos_esc = CicloEscModel::count();
        return view('catalogos.ciclosesc.index', compact('ciclos_esc','count_ciclos_esc'));
    }

    public function agregar()
    {
       
        $ciclo_esc_id = new CicloEscModel();

        return view('catalogos.ciclosesc.editar', compact('ciclo_esc_id'));
    }

    public function editar($ciclo_esc_id = null)
    {
        if ($ciclo_esc_id) {
            // Si hay un $alumno_id, busca el modelo y carga los datos
            $ciclo_esc_id = CicloEscModel::find($ciclo_esc_id);

            if (!$ciclo_esc_id) {
                // Manejar el caso en que el alumno no se encuentre
                abort(404); // O alguna otra acción que desees tomar
            }
        } else {
            // Si no hay $alumno_id, crea un nuevo modelo con campos vacíos
            $ciclo_esc_id = new CicloEscModel();
        }
        return view('catalogos.ciclosesc.editar', compact('ciclo_esc_id'));
    }

    public function agregarsuccess($ciclo_esc_id)
    {
        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'CiclosEscController',
            //'component'     =>  'FormComponent',
            'function'  =>  'agregarsuccess',
            'description'   =>  'se guardó ciclo_esc_id:'.$ciclo_esc_id,
        ]);
        return redirect()->route('catalogos.ciclosesc.index')->with('success','Ciclo escolar ID: '.$ciclo_esc_id.' guardado correctamente');
    }
    
    public function eliminar($ciclo_esc_id)
    {
        if(Auth()->user()->hasPermissionTo('ciclos_esc-borrar'))
        {
            $ciclos_esc = CicloEscModel::find($ciclo_esc_id);
            $ciclos_esc->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'CiclosEscController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó ciclo_esc_id:'.$ciclo_esc_id,
            ]);

            return redirect()->route('catalogos.ciclosesc.index')->with('warning','Se eliminó correctamente el ciclo_esc_id id:'.$ciclo_esc_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'CiclosEscController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usurio sin permisos',
            ]);

            return redirect()->route('catalogos.ciclosesc.index')->with('danger','No tiene los permisos necesarios');
        }
    }

}
