<?php

namespace App\Http\Controllers\Catalogos;

use App\Exports\Catalogos\AulasExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AulaModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Maatwebsite\Excel\Facades\Excel;

class AulaController extends Controller
{
    public function aulas_del_plantel($plantel_id)
    {
        Paginator::useBootstrap();
        
        $plantel = PlantelesModel::find($plantel_id);
        $aulas = AulaModel::where('plantel_id',$plantel->id)->paginate(10);

        return view('catalogos.aulas.index',compact('plantel','plantel_id','aulas'));
    }

    public function agregar_al_plantel($plantel_id)
    {
        return view('catalogos.aulas.agregar',compact('plantel_id'));
    }

    public function editar_aula($aula_id)
    {
        $aula = AulaModel::find($aula_id);
        return view('catalogos.aulas.editar',compact('aula'));
    }

    public function eliminar_aula($aula_id)
    {
        $aula = AulaModel::find($aula_id);
        if(Auth()->user()->hasPermissionTo('aula-borrar'))
        {
            
            $aula->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AulaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó aula id:'.$aula_id,
            ]);

            return redirect()->route('catalogos.plantel.aulas',$aula->plantel_id)->with('warning','Se eliminó correctamente el aula id:'.$aula_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AulaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar_aula',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.plantel.aulas',$aula->plantel_id)->with('danger','No tiene los permisos necesarios');
        }
    }

    public function excel_plantel($plantel_id)
    {
        return Excel::download(new AulasExport($plantel_id), 'sce-cobach-aulas-'.date("Y-m-d H:i:s").'.xlsx');
    }
}
