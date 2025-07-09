<?php

// ANA MOLINA 08/11/2023
namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;
// use Illuminate\Pagination\Paginator;
use Dompdf\Dompdf;
use PDF;
class BoletaController extends Controller
{

    public function index()
    {

        return view('adminalumnos.boleta.index');
    }


    public function reporte($alumno_id,$ciclo_id)
    {



        $calificaciones =session('calificaciones');
        $calificacionesex =session('calificacionesex');
       //$calificaciones = session(['calificaciones' ]);


       //return view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones' ));

       //return view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones'));

       //$pdf = PDF::loadView('adminalumnos.kardex.reporte');
//$data=compact('alumno_id','calificaciones' );
//dd($data);

         $pdf = PDF::loadView('adminalumnos.boleta.reporte', array('alumno_id'=>$alumno_id,'ciclo_id'=>$ciclo_id,'calificaciones'=>$calificaciones,'calificacionesex'=>$calificacionesex));
         return $pdf->stream('boleta'.$alumno_id .'.pdf');
         //return view('adminalumnos.kardex.reporte', ['alumno_id'=>$alumno_id,'calificaciones'=>$calificaciones] );



        // $view=view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones' ));
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        //  $pdf->stream();
        // return view('adminalumnos.kardex.reporte')->with('alumno_id', $alumno_id)->with('calificaciones', $calificaciones);
        //datos fijos
       //$pdf = PDF::loadView('adminalumnos.kardex.reporte');
       //return $pdf->stream('kardex'.$alumno_id .'.pdf');


    //    $pdf = PDF::loadView('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones' ));
    //    return $pdf->download('kardex'.$alumno_id .'.pdf');




       $view= view('adminalumnos.boleta.reporte', compact('alumno_id','ciclo_id','calificaciones' ,'calificacionesex' ));//->render();
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHTML($view);
        //$dompdf->loadHTML($html);

        // (Optional) Setup the paper size and orientation
        //$dompdf->setPaper('letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $pdf = $dompdf->output();
        // Output the generated PDF to Browser
        $dompdf->stream();


    }


    public function reportegrupo($gr)
    {

        //return view('adminalumnos.boleta.reportegrupo', array('grupos_sel'=>$gr));//->render();;

         $pdf = PDF::loadView('adminalumnos.boleta.reportegrupo', array('grupos_sel'=>$gr));
         return $pdf->stream('boletagrupos.pdf');



    }


}





