<?php

namespace App\Http\Livewire\Certificados\Sibal;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\CalificacionesModel;
use Livewire\Component;

class Genera extends Component
{

    public $alumno_id = 0, $alumno, $validacion = false, $asignaturas_sibal;
    public $calificaciones = []; 
    
    
    public function cambia_duplicado()
    {
        if ($this->alumno->carta_compromiso) {
            $this->alumno->carta_compromiso = null;
        } else {
            $this->alumno->carta_compromiso = now();
        }
        $this->alumno->save();
    }

    public function render()
    {
        $this->alumno = AlumnoModel::find($this->alumno_id);

        if ($this->alumno) {
            $buscar_calificaciones = CalificacionesModel::join('esc_curso', 'esc_curso.id', '=', 'esc_calificacion.curso_id')
                ->where('alumno_id', $this->alumno_id)
                ->where('esc_curso.curso_tipo', '15')
                ->get();


                $this->asignaturas_sibal = CursosModel::leftJoin('esc_calificacion', function($join) {
                    $join->on('esc_calificacion.curso_id', '=', 'esc_curso.id')
                         ->where('esc_calificacion.alumno_id', '=', $this->alumno_id);
                })
                ->leftJoin('alu_alumno', 'alu_alumno.id', '=', 'esc_calificacion.alumno_id')
                ->join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_curso.asignatura_id')
                ->leftJoin('esc_grupo', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
                ->select('esc_curso.*', 'esc_calificacion.id as id_calif', 'asi_asignatura.clave', 'esc_calificacion.calificacion', 'esc_grupo.nombre as modulo')
                ->where('esc_curso.curso_tipo', 15)
                ->get();
            
                

            // Inicializa las calificaciones con los valores existentes
            foreach ($this->asignaturas_sibal as $asignatura) {
                $this->calificaciones[$asignatura->id] = $asignatura->calificacion;
            }

            $this->validacion = $this->asignaturas_sibal->pluck('id_calif')->contains(function ($value) {
                return !is_null($value);
            });
            /*
            if ($buscar_calificaciones->count() > 0) {
                $this->validacion = false;
            } else {
              
            }*/
        }
        return view('livewire.certificados.sibal.genera');
    }

    public function mount(){
     
    }

    public function actualizarCalificacion($curso_id, $nuevaCalificacion)
    {
        // Actualiza o crea la calificación en la base de datos
        
        if($nuevaCalificacion){
            CalificacionesModel::updateOrCreate(
                [
                    'curso_id' => $curso_id,
                    'alumno_id' => $this->alumno_id,
                ],
                [
                    'calificacion' => $nuevaCalificacion,
                ]
            );
    
            // Actualiza el valor en la propiedad $calificaciones
            $this->calificaciones[$curso_id] = $nuevaCalificacion;
        }
        
        
    }
}
