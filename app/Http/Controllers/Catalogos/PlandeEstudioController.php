<?php
// ANA MOLINA 08/08/2023
namespace App\Http\Controllers\Catalogos;
use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PlandeEstudioModel;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class PlandeEstudioController extends Controller
{
    public function index()
    {
        return view('catalogos.planesdeestudio.index' );
    }

    public function agregar()
    {

        return view('catalogos.planesdeestudio.agregar');
    }

    public function editar($plan_id)
    {
        return view('catalogos.planesdeestudio.editar', compact('plan_id'));
    }

    public function eliminar($plan_id)
    {
        if(Auth()->user()->hasPermissionTo('plandeestudio-borrar'))
        {
            $plan =PlandeEstudioModel::find($plan_id);
            $plan->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'PlandeEstudioController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó plan de estudio id:'.$plan_id,
            ]);

            return redirect()->route('catalogos.planesdeestudio.index')->with('warning','Se eliminó correctamente el plan de estudio id:'.$plan_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'PlanesdeEstudioController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('catalogos.planesdeestudio.index')->with('danger','No tiene los permisos necesarios');
        }
    }

    public function reporte()
    {

        //return view('catalogos.planesdeestudio.reporte');
        $view= view('catalogos.planesdeestudio.reporte');//->render();
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
