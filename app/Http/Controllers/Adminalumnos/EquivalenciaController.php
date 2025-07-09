<?php
// ANA MOLINA 10/07/2024
namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Adminalumnos\EquivalenciacalifModel;
use App\Models\Adminalumnos\EquivalenciaModel;

use App\Models\Adminalumnos\RevalidacioncalifModel;
use App\Models\Adminalumnos\RevalidacionModel;
use Illuminate\Http\Request;
use PDF;
class EquivalenciaController extends Controller
{
    public function index()
    {
        return view('adminalumnos.equivalencia.index');
    }

    public function agregar()
    {

        return view('adminalumnos.equivalencia.agregar');
    }

    public function editar($equi_id,$tip)
    {
         //decodificar
         $equivalencia_id =  base64_decode($equi_id);
         $tipo =  base64_decode($tip);
        return view('adminalumnos.equivalencia.editar', compact('equivalencia_id','tipo'));
    }

    public function eliminar($equivalencia_id,$tipo)
    {
        //decodificar
        $decequiid =  base64_decode($equivalencia_id);
        $dectipo =  base64_decode($tipo);


        if(Auth()->user()->hasPermissionTo('equivalencia-borrar'))
        {
            if ($dectipo=='E')
            {
                EquivalenciacalifModel::where('id',$decequiid)->delete();

                $equivalencia = EquivalenciaModel::find($decequiid);
                $equivalencia->delete();
            }
            elseif ($dectipo=='R')
            {
                

                RevalidacioncalifModel::where('id',$decequiid)->delete();

                $equivalencia = RevalidacionModel::find($decequiid);
                $equivalencia->delete();
            }

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'EquivalenciaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó equivalencia id:'.$decequiid,
            ]);

            return redirect()->route('adminalumnos.equivalencia.index')->with('warning','Se eliminó correctamente la Revalidacion id:'.$decequiid);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'EquivalenciaController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('adminalumnos.equivalencia.index')->with('danger','No tiene los permisos necesarios');
        }
    }
    public function reporte($equi_id,$tip)
    {
         //decodificar
         $equivalencia_id =  base64_decode($equi_id);
         $tipo =  base64_decode($tip);


       // return view('adminalumnos.equivalencia.reporte', array('equivalencia_id'=>$equivalencia_id,'tipo'=>$tipo));//->render();

         $pdf = PDF::loadView('adminalumnos.equivalencia.reporte', array('equivalencia_id'=>$equivalencia_id,'tipo'=>$tipo));
        //dd($al, $grupo_id);

        if($tipo=='E')
            $file='equivalencia.pdf';
        else
        if($tipo=='R')
            $file='revalidacion.pdf';
         return $pdf->stream($file);


    }
}
