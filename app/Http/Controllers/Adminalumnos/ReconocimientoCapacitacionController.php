<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;
use PDF;

class ReconocimientoCapacitacionController extends Controller
{
    //
    public function index()
    {
        if(Auth()->user()->can('reconocimiento_capacitacion'))
        {
            return view('adminalumnos.reconocimiento_capacitacion.index');
        }
        else
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'ReconocimientoCapacitacionController',
                //'component'     =>  'FormComponent',
                'function'  =>  'index',
                'description'   =>  'Usuario sin permisos',
            ]);

            return redirect()->route('dashboard')->with('danger','No tiene los permisos necesarios');
        }
        
    }

    public function descargar_diplomas()
    {
        $alumnos = session('alumnos');
        $nombre_archivo = session('nombre_archivo');
        //dd($alumnos);
        $datos_vista = [
            'alumnos'   => $alumnos,
        ];

        $pdf = PDF::loadView('adminalumnos.reconocimiento_capacitacion.diploma_capacitacion',$datos_vista);
        //$pdf->setPaper('A4', 'landscape');
        $pdf->setPaper('A4');
        return $pdf->download('DIPLOMAS '.$nombre_archivo.'.pdf');

    }

    public function descargar_diplomas_sf()
    {
        $alumnos = session('alumnos');
        $nombre_archivo = session('nombre_archivo');
        //dd($alumnos);
        $datos_vista = [
            'alumnos'   => $alumnos,
        ];

        $pdf = PDF::loadView('adminalumnos.reconocimiento_capacitacion.diploma_capacitacion_sf',$datos_vista);
        //$pdf->setPaper('A4', 'landscape');
        $pdf->setPaper('A4');
        return $pdf->download('DIPLOMAS SF '.$nombre_archivo.'.pdf');

    }

    public function liga_diploma($alumno_id)
    {
        $alumno = AlumnoModel::find($alumno_id);
        $alumnos = [$alumno->id => true];

        $nombre_archivo = '_capacitacion_'.$alumno->noexpediente;
        $datos_vista = [
            'alumnos'   => $alumnos,
        ];

        $pdf = PDF::loadView('adminalumnos.reconocimiento_capacitacion.diploma_capacitacion_unic_liga',$datos_vista);
        //$pdf->setPaper('A4', 'landscape');
        $pdf->setPaper('A4');
        return $pdf->download('DIPLOMAS_'.$nombre_archivo.'.pdf');
    }


}
