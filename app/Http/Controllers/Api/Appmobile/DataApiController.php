<?php
// ANA MOLINA 12/12/2025
namespace App\Http\Controllers\Api\Appmobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appmobile\DataRequest;
use App\Http\Resources\Appmobile\DataResource;


use  App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Spatie\Permission\Models\Role;

class DataApiController extends Controller
{


    public function getTest()
    {


                $noexpediente="23240794";
                $alumno = DB::table('alu_alumno')

               ->where('alu_alumno.noexpediente', $noexpediente)
                ->select(
                    'id','nombre','apellidos','noexpediente'
                )
                ->get();

            return new DataResource($alumno);
    }
    public function getTestParameter($noexpediente)
    {



                $alumno = DB::table('alu_alumno')

               ->where('alu_alumno.noexpediente', $noexpediente)
                ->select(
                    'id','nombre','apellidos','noexpediente'
                )
                ->get();

            return new DataResource($alumno);
    }
    public function getAlumno($noexpediente)
    {
                $alumno = DB::table('alu_alumno')
               ->where('alu_alumno.noexpediente', $noexpediente)
                ->select(
                    'id','nombre','apellidos','noexpediente'
                )
                ->get();

            return new DataResource($alumno);
    }
    public function getImagen($alumno_id)
    {
        $img=null;
        $imagen_find = ImagenesalumnoModel::where('alumno_id',$alumno_id)->where('tipo',1)->select(  'imagen' )->get();
        if ($imagen_find->count()>0)
        {
            //$img=$imagen_find[0]['imagen'];
            $img=$imagen_find[0]->imagen;
        }
       // return $img;
       //return new DataResource($imagen_find);
       //return "data:image/png;base64,".chunk_split(base64_encode($img));
       return base64_encode($img);
    }
    public function getGrupo($alumno_id)
    {
                $grupo = DB::table('esc_grupo_alumno')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
                ->join('cat_turno', 'cat_turno.id', '=', 'esc_grupo.turno_id')
               ->where('esc_grupo_alumno.alumno_id', $alumno_id)
               ->select('esc_grupo.nombre as grupo','cat_turno.nombre as turno','gpo_base')
               ->distinct('esc_grupo.nombre','cat_turno.nombre ')
                ->get();
            return new DataResource($grupo);
    }
    public function getCalificaciones($alumno_id,$parcial)
    {
        $calificaciones = DB::select('CALL pa_alumno_calificaciones(?,?)', array($alumno_id,$parcial)) ;
        return new DataResource($calificaciones);

    }
    public function validaemail($email)
    {
                //únicamente correos de alumnos inscritos en el ciclo que se encuentra activo
               $ciclos=CicloEscModel::where ('activo',1)->first();

               $alumno = DB::table('alu_alumno')
               ->join('esc_grupo_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
               ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
               ->where('alu_alumno.correo_institucional', $email )
               ->where('esc_grupo.ciclo_esc_id', $ciclos->id)
                ->select(
                    'alu_alumno.id','alu_alumno.nombre','apellidos','noexpediente'
                )
                ->get();
            return new DataResource($alumno);

    }
}
