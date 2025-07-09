<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Models\Escolares\CalificacionesCicloAnterior;
use App\Models\Escolares\CalificacionesModel;
use Illuminate\Http\Request;

class Calificaciones_por_alumno extends Controller
{
    public function getCalificacionAlumno($id_alumno, $ciclo_esc_id)
    {
        $calificaciones = CalificacionesModel::select(
            'esc_curso.asignatura_id',
            'esc_curso.nombre',
            'esc_calificacion.calificacion',
            'esc_calificacion.calif',
            'esc_calificacion.calificacion_tipo'
        )
            ->join('esc_curso', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
            ->join('asi_asignatura', 'esc_curso.asignatura_id', '=', 'asi_asignatura.id')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->where('esc_calificacion.calificacion_tipo', 'Final')
            ->where('esc_calificacion.alumno_id', $id_alumno)
            ->where('esc_grupo.ciclo_esc_id', '=', $ciclo_esc_id)
            ->get();


        return response()->json($calificaciones);
    }
}
