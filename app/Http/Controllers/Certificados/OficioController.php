<?php
// ANA MOLINA 17/07/2024
namespace App\Http\Controllers\Certificados;
use App\Http\Controllers\Controller;
use App\Certificado\Oficio;
use App\Models\Administracion\BitacoraModel;

use App\Models\Certificados\CertificadovalModel;

use App\Models\Certificados\CertificadovalaModel;
use Illuminate\Http\Request;

// use Illuminate\Pagination\Paginator;
use Dompdf\Dompdf;
use PDF;
class OficioController extends Controller
{

    public function oficio()
    {
        return view('certificados.valida.oficio');
    }
    public function agregar()
    {

        return view('certificados.valida.agregar');
    }

    public function editar($oficio_id)
    {
        return view('certificados.valida.editar', compact('oficio_id'));
    }

    public function eliminar($oficio_id)
    {
        if(Auth()->user()->hasPermissionTo('oficio-borrar'))
        {
            $oficioa = CertificadovalaModel::find($oficio_id);
            $oficioa->delete();

            $oficio = CertificadovalModel::find($oficio_id);
            $oficio->delete();

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'OficioController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Eliminó oficio id:'.$oficio_id,
            ]);

            return redirect()->route('certificados.valida.oficio')->with('warning','Se eliminó correctamente el oficio id:'.$oficio_id);
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'OficioController',
                //'component'     =>  'FormComponent',
                'function'  =>  'eliminar',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('certificados.valida.oficio')->with('danger','No tiene los permisos necesarios');
        }
    }
    public function oficio_autenticidad($oficio_id)
    {
        //decodificar
        $sel_oficio=base64_decode($oficio_id);

        $pdf = Oficio::oficio_autenticidad($sel_oficio);
        return $pdf->stream('oficio_autenticidad.pdf');

    }
    public function oficio_apocrifo($oficio_id)
    {
        //decodificar
        $sel_oficio=base64_decode($oficio_id);

        $pdf = Oficio::oficio_apocrifo($sel_oficio);
        return $pdf->stream('oficio_apocrifo.pdf');
    }

}
