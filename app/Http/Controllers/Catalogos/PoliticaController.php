<?php
// ANA MOLINA 29/06/2023
namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PoliticaModel;
use App\Models\Catalogos\Politica_variableModel;
use App\Models\Catalogos\Politica_variableletradetModel;
use Illuminate\Http\Request;

class PoliticaController extends Controller
{
    public function index()
    {
        return view('catalogos.politicas.index');
    }

    public function agregar()
    {
        return view('catalogos.politicas.agregar');
    }

    public function editar($politica_id)
    {
        return view('catalogos.politicas.editar', compact('politica_id'));
    }
    public function formula($politica_id)
    {
        return view('catalogos.politicas.formula', compact('politica_id'));
    }

    public function eliminar($politica_id)
    {
        if(Auth()->user()->hasPermissionTo('politica-borrar'))
        {
            $variablesPolitica=Politica_variableModel::where('id_politica',$this->politica_id)->get();


            foreach ($variablesPolitica as $variable) {
                Politica_variableletradetModel::where('id_politicavariable',$variable->id)->delete();
            }

            Politica_variableModel::where('id_politica',$this->politica_id)->delete();

            $politica= PoliticaModel::find($politica_id);
            $politica->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'PoliticaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó politica id:'.$politica_id,
            ]);

            return redirect()->route('catalogos.politicas.index')->with('warning','Se eliminó correctamente el politica id:'.$politica_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'PoliticaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.politicas.index')->with('danger','No tiene los permisos necesarios');
        }
    }

}
