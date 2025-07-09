<?php

// ANA MOLINA 28/08/2023
namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Pagination\Paginator;
use Dompdf\Dompdf;
use PDF;
class KardexController extends Controller
{

    public function index()
    {

        return view('adminalumnos.kardex.index');
    }

   
    public function reporte($alumno_id = null, $grupo_id = null)
    {
        $imagen_logo = ImagenesalumnoModel::where('id',72245)->where('tipo',2)->first();

        $alumno = AlumnoModel::where('correo_institucional', Auth()->user()->email)->first();

        if($alumno){
            $calificacioneska =session('calificacioneska');
            
            $pdf = PDF::loadView('adminalumnos.kardex.reporte', array('alumno_id'=>$alumno->id,'calificacioneska'=>false, 'grupo_id'=>$grupo_id, 'imagen_logo' => $imagen_logo));
            return $pdf->stream('kardex'.$alumno_id .'.pdf');
        }else{
            if($alumno_id !=0){
                $calificacioneska =session('calificacioneska');
    
    
                $pdf = PDF::loadView('adminalumnos.kardex.reporte', array('alumno_id'=>$alumno_id,'calificacioneska'=>$calificacioneska, 'grupo_id'=>$grupo_id, 'imagen_logo' => $imagen_logo));
                return $pdf->stream('kardex'.$alumno_id .'.pdf');
            }
            else{ 
                $alumnos_en_plantel_periodo = DB::table('esc_grupo_alumno')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                ->select('esc_grupo_alumno.alumno_id')
                ->where('esc_grupo.id', $grupo_id)
                ->groupBy('esc_grupo_alumno.alumno_id', 'alu_alumno.apellidos', 'grupo_id')
                ->orderBy('alu_alumno.apellidos', 'asc')
                ->get();
                $grupo = GruposModel::find($grupo_id);
                $grupo_nombre = $grupo->nombre;
                $plantel = $grupo->plantel->nombre;
                $pdf = new Dompdf();
                $pdf->setPaper('A4', 'portrait');
            
                $pdfContent = '';
                
            
                foreach ($alumnos_en_plantel_periodo as $alumno) {
                    $calificacioneska = session('calificacioneska'); // Ajustar esto para obtener las calificaciones específicas para cada alumno
            
                    $viewContent = view('adminalumnos.kardex.reporte', array('alumno_id' => $alumno->alumno_id, 'calificacioneska' => $calificacioneska, 'grupo_id' => $grupo_id, 'imagen_logo' => $imagen_logo));
                    
                    // Append each rendered view as a new page
                    $pdfContent .= '<div style="page-break-after: always;">' . $viewContent . '</div>';
                }
            
                $pdf->loadHtml($pdfContent);
                $pdf->render();
                
                return $pdf->stream('Kardex_'.$grupo_nombre.'_'.$grupo->turno->nombre.'_'.$plantel.'.pdf');
            }
        }
        
        //$calificaciones = session(['calificaciones' ]);
        //dd($calificacioneska);
        //return view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones' ));
        //return view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones'));
        //$pdf = PDF::loadView('adminalumnos.kardex.reporte');
        //$data=compact('alumno_id','calificaciones' );
        //dd($data);
      
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
    /*
       $view= view('adminalumnos.kardex.reporte', compact('alumno_id','calificacioneska' ));//->render();
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

*/
    }


    public function reporte_hist_academ($alumno_id = null, $grupo_id = null)
    {
        $imagen_logo = ImagenesalumnoModel::where('id',72245)->where('tipo',2)->first();


        if($alumno_id !=0){
            $calificacioneska =session('calificacioneska');


            $pdf = PDF::loadView('adminalumnos.kardex.reporte_hist_academ', array('alumno_id'=>$alumno_id,'calificacioneska'=>$calificacioneska, 'grupo_id'=>$grupo_id, 'imagen_logo' => $imagen_logo));
            return $pdf->stream('Hist_Academico_'.$alumno_id .'.pdf');
        }
        else{ 
            $alumnos_en_plantel_periodo = DB::table('esc_grupo_alumno')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
            ->select('esc_grupo_alumno.alumno_id')
            ->where('esc_grupo.id', $grupo_id)
            ->groupBy('esc_grupo_alumno.alumno_id', 'alu_alumno.apellidos', 'grupo_id')
            ->orderBy('alu_alumno.apellidos', 'asc')
            ->get();
            $grupo = GruposModel::find($grupo_id);
            $grupo_nombre = $grupo->nombre;
            $plantel = $grupo->plantel->nombre;
            $pdf = new Dompdf();
            $pdf->setPaper('A4', 'portrait');
        
            $pdfContent = '';
            
        
            foreach ($alumnos_en_plantel_periodo as $alumno) {
                $calificacioneska = session('calificacioneska'); // Ajustar esto para obtener las calificaciones específicas para cada alumno
        
                $viewContent = view('adminalumnos.kardex.reporte_hist_academ', array('alumno_id' => $alumno->alumno_id, 'calificacioneska' => $calificacioneska, 'grupo_id' => $grupo_id, 'imagen_logo' => $imagen_logo));
                
                // Append each rendered view as a new page
                $pdfContent .= '<div style="page-break-after: always;">' . $viewContent . '</div>';
            }
        
            $pdf->loadHtml($pdfContent);
            $pdf->render();
            
            return $pdf->stream('Hist_Academico_'.$grupo_nombre.'_'.$grupo->turno->nombre.'_'.$plantel.'.pdf');
        }

        
        //$calificaciones = session(['calificaciones' ]);
        //dd($calificacioneska);
        //return view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones' ));
        //return view('adminalumnos.kardex.reporte', compact('alumno_id','calificaciones'));
        //$pdf = PDF::loadView('adminalumnos.kardex.reporte');
        //$data=compact('alumno_id','calificaciones' );
        //dd($data);
      
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
    /*
       $view= view('adminalumnos.kardex.reporte', compact('alumno_id','calificacioneska' ));//->render();
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

*/
    }

    public function calificaciones($expediente){
        $calificaciones_alumno = CalificacionesModel::select('esc_calificacion.*', 'esc_curso.nombre', 
        'cat_ciclos_esc.nombre as ciclo', 'alu_alumno.noexpediente')
        ->join('esc_curso', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
        ->join('esc_grupo', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
        ->join('alu_alumno', 'alu_alumno.id', '=', 'esc_calificacion.alumno_id')
        ->where(function($query) {
            $query->where('esc_calificacion.calificacion_tipo', 'Final')
                  ->orWhere('esc_calificacion.calificacion_tipo', 'R');
                }) // Filtrar por calificacion_tipo igual a 'Final' o 'R'
        ->orderby('ciclo', 'asc')
        ->where('noexpediente', $expediente)
        ->get();

        return view('adminalumnos.boleta.consulta')->with('calificaciones', $calificaciones_alumno)->with('expediente', $expediente);
    }

}
