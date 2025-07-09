<?php

namespace App\Http\Controllers\Grupos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Http\Request;
use Livewire\Livewire;

class crearGrupo extends Controller
{
    public function index()
    {
        return view('grupos.index');
    }

    public function create()
    {
        return view('grupos.agregar');
    }

    public function update($id_grupo)
    {
        return view('grupos.update', compact('id_grupo'));

    }

    public function eliminar($id_grupo){
        if(Auth()->user()->hasPermissionTo('grupos-borrar'))
        {
            $Grupo = GruposModel::find($id_grupo);
            $Grupo->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'GruposController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó el grupo con el id:'.$id_grupo,
            ]);

            return redirect()->route('Grupos.crear.index')->with('warning','Se eliminó correctamente el grupo con el id:'.$id_grupo);
    }

}
}
