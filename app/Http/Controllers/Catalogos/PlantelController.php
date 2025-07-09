<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class PlantelController extends Controller
{
    public function index()
    {
        return view('catalogos.planteles.index');
    }

    public function agregar()
    {

        return view('catalogos.planteles.agregar');
    }

    public function editar($plantel_id)
    {
        return view('catalogos.planteles.editar', compact('plantel_id'));
    }

    public function eliminar($plantel_id)
    {
        if(Auth()->user()->hasPermissionTo('plantel-borrar'))
        {
            $plantel = PlantelesModel::find($plantel_id);
            $plantel->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'PlantelController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó plantel id:'.$plantel_id,
            ]);

            return redirect()->route('catalogos.planteles.index')->with('warning','Se eliminó correctamente el plantel id:'.$plantel_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'PlantelController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.planteles.index')->with('danger','No tiene los permisos necesarios');
        }
    }
    public function reporte()
    {

        //return view('catalogos.planteles.reporte');
        $view= view('catalogos.planteles.reporte');//->render();
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHTML($view);
        //$dompdf->loadHTML($html);

        // (Optional) Setup the paper size and orientation
        //$dompdf->setPaper('letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }

}
