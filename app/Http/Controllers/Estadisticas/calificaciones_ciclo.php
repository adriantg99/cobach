<?php

namespace App\Http\Controllers\estadisticas;

use App\Exports\Estadisticas\CalificacionesExport;
use App\Http\Controllers\Controller;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class calificaciones_ciclo extends Controller
{
    public function exportar_excel(){
        $ciclo_activo = CicloEscModel::where('activo', '1')->first();
        $calificaciones = GruposModel::where('ciclo_esc_id', $ciclo_activo->id)
        ->join('esc_grupo_alumno as ga', 'esc_grupo.id', '=', 'ga.grupo_id')
        ->join('esc_curso as c', 'esc_grupo.id', '=', 'c.grupo_id')
        ->join('asi_asignatura as a', 'c.asignatura_id', '=', 'a.id')
        ->join('alu_alumno as al', 'ga.alumno_id', '=', 'al.id')
        ->leftJoin('emp_perfil as p', 'c.docente_id', '=', 'p.id')
        ->leftJoin('esc_calificacion as ec', function ($join) {
            $join->on('c.id', '=', 'ec.curso_id')
                ->on('ga.alumno_id', '=', 'ec.alumno_id');
        })
        ->leftJoin('cat_plantel as cp', 'esc_grupo.plantel_id', '=', 'cp.id')
        ->select(
            'al.noexpediente',
            'al.nombre as NOMBRE_ALUMNO',
            'al.apellidos as APELLIDOS_ALUMNO',
            'c.nombre as nombre_curso',
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 1 OR ec.calificacion_tipo = "P1") THEN ec.calificacion 
                ELSE NULL 
            END) AS parcial1'),
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 1 OR ec.calificacion_tipo = "P1") THEN ec.faltas 
                ELSE 0 
            END) AS faltas_parcial1'),
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 2 OR ec.calificacion_tipo = "P2") THEN ec.calificacion 
                ELSE NULL 
            END) AS parcial2'),
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 2 OR ec.calificacion_tipo = "P2") THEN ec.faltas 
                ELSE 0 
            END) AS faltas_parcial2'),
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 3 OR ec.calificacion_tipo = "P3") THEN ec.calificacion 
                ELSE NULL 
            END) AS parcial3'),
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 3 OR ec.calificacion_tipo = "P3") THEN ec.faltas 
                ELSE 0 
            END) AS faltas_parcial3'),
            \DB::raw('MAX(CASE 
                WHEN (ec.politica_variable_id = 5 OR ec.calificacion_tipo = "final") THEN ec.calificacion 
                ELSE NULL 
            END) AS calificacion_final'),
            'esc_grupo.periodo as GRADO',
            'esc_grupo.nombre as grupo_nombre',
            'esc_grupo.descripcion as DESCRIPCION_GRUPO',
            \DB::raw('MAX(CASE WHEN esc_grupo.turno_id = 1 THEN "MATUTINO" ELSE "VESPERTINO" END) as TURNO'),
            'cp.nombre as PLANTEL',
            'p.nombre as DOCENTE_NOMBRE',
            'p.apellido1 as DOCENTE_APELLIDO_PATERNO',
            'p.apellido2 as DOCENTE_APELLIDO_MATERNO'
        )
        ->groupBy('ga.alumno_id', 'esc_grupo.id', 'c.id', 'c.nombre')
        ->orderBy('ga.alumno_id', 'asc')
        ->get();

    return Excel::download(new CalificacionesExport($calificaciones), 'Calificaciones_TOTAL.xlsx');
    }
}
