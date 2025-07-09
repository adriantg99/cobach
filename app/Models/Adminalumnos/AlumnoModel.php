<?php

namespace App\Models\Adminalumnos;

use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Cursos\CursosOmitidosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Escolares\ExcepcionCertificadosModel;
use App\Models\escolares\ExcepcionesCertificadosModel;
use App\Models\Grupos\GruposModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class AlumnoModel extends Model
{

    /* Traits */

    use HasFactory;

    /* Properties */
    protected $connection = "mysql";

    protected $table = "alu_alumno";

    protected $fillable = [
        'plantel_id',
        'cicloesc_id',
        'planestudio_id',
        'noexpediente',
        'nombre',
        'apellidos',
        'apellidopaterno',
        'apellidomaterno',
        'correo_institucional',
        'domicilio',
        'domicilio_entrecalle',
        'domicilio_nointerior',
        'domicilio_noexterior',
        'colonia',
        'codigopostal',
        'telefono',
        'celular',
        'email',
        'fechanacimiento',
        'edad',
        'sexo',
        'estatura',
        'peso',
        'curp',
        'id_periodo',
        'fecharegistro',
        'fechabaja',
        'meds_permit',
        'id_secundaria_procedencia',
        'secundaria_nombre',
        'secundaria_clave',
        'secundaria_promedio',
        'secundaria_fechaegreso',
        'observaciones',
        'alergias',
        'alergias_describe',
        'discapacidad_describe',
        'folio_prepason',
        'tiposangre',
        'tutor_nombre',
        'tutor_apellido1',
        'tutor_apellido2',
        'tutor_domicilio',
        'tutor_colonia',
        'tutor_telefono',
        'tutor_ocupacion',
        'tutor_celular',
        'familiar_nombre',
        'familiar_apellido1',
        'familiar_apellido2',
        'familiar_celular',
        'tutor_email',
        'madre_nombre',
        'madre_celular',
        'id_nacionalidad',
        'id_lugarnacimiento',
        'id_paisnacimiento',
        'id_localidadnacimiento',
        'id_localidaddomicilio',
        'turno_especial',
        'id_beca',
        'beca_otra',
        'id_servicio_medico',
        'servicio_medico_otro',
        'servicio_medico_afiliacion',
        'deudas_finanzas',
        'deudas_finanzas_desc',
        'deudas_biblioteca',
        'deudas_biblioteca_desc',
        'empresa_nombre',
        'empresa_domicilio',
        'empresa_telefono',
        'empresa_colonia',
        'tutor_empresa_nombre',
        'tutor_empresa_domicilio',
        'tutor_empresa_telefono',
        'tutor_empresa_colonia',
        'id_discapacidad',
        'enfermedad',
        'id_etnia',
        'lengua_indigena',
        'lengua_indigena_desc',
        'extranjero_padre_mexicano',
        'extranjero_grado_ems',
        'extranjero_habla_espanol',
        'extranjero_escribe_espanol',
        'extranjero_lee_espanol',
        'id_extranjero_paisnacimiento',
        'id_extranjero_paisestudio',
        'id_estatus',					// 0=Inactivo, 1=Activo, 2=Baja
        'carta_compromiso'
    ];

    /* Relations HasMany */

    public function calificaciones(): HasMany
    {
        return $this->hasMany(CalificacionesModel::class, 'alumno_id', 'id');
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenesalumnoModel::class, 'alumno_id', 'id');
    }

    /* Relations BelongsToMany Pivot */

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(GruposModel::class, 'esc_grupo_alumno', 'alumno_id', 'grupo_id')
            ->using(GrupoAlumnoModel::class)
            ->as('grupo_alumno')
            ->withTimestamps();
    }

    /* Methods */

    public function plantel()
    {
        $sql = "SELECT esc_grupo.nombre AS grupo, esc_grupo.id, ";
        $sql = $sql . "CASE turno_id WHEN 1 THEN 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, ";
        $sql = $sql . "cat_ciclos_esc.activo AS ciclo_activo, cat_plantel.nombre AS plantel, cat_plantel.id AS plantel_id, cat_ciclos_esc.nombre, cat_ciclos_esc.id AS id_ciclo ";
        $sql = $sql . "FROM esc_grupo_alumno ";
        $sql = $sql . "LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id ";
        $sql = $sql . "LEFT JOIN cat_ciclos_esc ON cat_ciclos_esc.id=esc_grupo.ciclo_esc_id ";
        $sql = $sql . "LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id ";
        $sql = $sql . "WHERE ((esc_grupo_alumno.alumno_id=" . $this->id . ") ";
        $sql = $sql . "AND (plantel_id != 34) AND (esc_grupo.nombre <> 'ActasExtemporaneas')";
        $sql = $sql . ") ORDER BY cat_ciclos_esc.per_inicio DESC ";
        //$sql = $sql."LIMIT 1";
        //dd($sql);
        $data = DB::select($sql);
        //dd($data);
        if (count($data) > 0) {
            $datos = $data[0];
            $plantel = PlantelesModel::find($datos->plantel_id);
            return $plantel;
        } else {
            $datos = null;
            return null;
        }
    }

    public function cursos_del_grupo($grupo_id, $alumno_id)
    {
        //Devuelve listado de cursos quitando los omitidos de un grupo para el alumno $this->id
        $cursos_omitidos = CursosOmitidosModel::where('alumno_id', $this->id)->get();
        $data = null;
        $grupo = GruposModel::find($grupo_id);
        if ($cursos_omitidos) {
            foreach ($cursos_omitidos as $co) {
                $data[] = $co->curso_id;
            }
        }
        $ciclo_esc = CicloEscModel::find($grupo->ciclo_esc_id);
        //cat_ciclos_esc.per_final','>', $fechaHoy
        $fechaHoy = Carbon::now();
        if ($ciclo_esc->per_final >= $fechaHoy) {
            if ($data) {
                $cursos = CursosModel::where('grupo_id', $grupo_id)
                    ->whereNotIn('id', $data)
                    ->get();
            } else {
                $cursos = CursosModel::where('grupo_id', $grupo_id)->get();
            }
        } else {
            $data = null;
            $cursos_con_calif = CalificacionesModel::select('curso_id')
                ->distinct()->where('alumno_id', $alumno_id)->get();
            if ($cursos_con_calif) {
                foreach ($cursos_con_calif as $ccc) {
                    $data[] = $ccc->curso_id;
                }
            }
            if ($data) {
                $cursos = CursosModel::where('grupo_id', $grupo_id)
                    ->whereIn('id', $data)
                    ->get();
            } else {
                $cursos = null;
            }
        }
        return $cursos;
    }

    public function capacitacion()
    {
        //A QUE CAPACITACION PERTENECE EL ALUMNO

        
        $res = null;
        $sql = "SELECT distinct(clave)
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
AND esc_calificacion.calificacion_tipo_id <> 0
    AND (asi_asignatura.clave = '5130101' OR asi_asignatura.clave = '6130101' OR asi_asignatura.clave = '6110101' OR asi_asignatura.clave = '6120101'))";

   $res = DB::select($sql);

        if (count($res) >= 4) {
            return 'DES_MICR_ANTERIOR';
        }


        $res = null;
        $sql = "SELECT distinct(clave)
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
AND esc_calificacion.calificacion_tipo_id <> 0
    AND (asi_asignatura.clave = '5130101' OR asi_asignatura.clave = '6130101' OR asi_asignatura.clave = '6110101' OR asi_asignatura.clave = '6120101'))";

   $res = DB::select($sql);

        if (count($res) >= 4) {
            return 'DES_MICR_ANTERIOR';
        }

        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31301%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'DES_MICR';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31302%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'CONTABIL';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31303%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'INFORMAT';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31304%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'ING_P_RE';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31305%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'COMUNICA';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31306%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'SER_TURI';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31307%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'TEC_D_CO';
        }

        //-----
        $res = null;
        $sql = "SELECT esc_calificacion.alumno_id
FROM esc_calificacion 
INNER JOIN esc_curso ON esc_calificacion.curso_id = esc_curso.id
INNER JOIN asi_asignatura ON esc_curso.asignatura_id = asi_asignatura.id
WHERE ((esc_calificacion.alumno_id = " . $this->id . ") 
    AND (asi_asignatura.clave LIKE '31308%'))";

        $res = DB::select($sql);

        if (count($res) > 0) {
            return 'GASTRONO';
        }

    }
    public function buscar_ciclo($alumno_id, $periodo, $nombre_asi)
    {
        $ciclo = CursosModel::select('rango')
            ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
            ->join('esc_grupo_alumno', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
            ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
            ->join('esc_calificacion', function ($join) use ($alumno_id) {
                $join->on('esc_grupo_alumno.alumno_id', '=', 'esc_calificacion.alumno_id')
                    ->on('esc_curso.id', '=', 'esc_calificacion.curso_id')
                    ->where('esc_calificacion.alumno_id', '=', $alumno_id);
            })
            ->where('esc_curso.nombre', $nombre_asi)
            ->orderBy('per_inicio')
            ->first();

        /*
        if ($ciclos->count() === 1) {
            // Si solo hay un único rango, omite el foreach
            $rangoSeleccionado = $ciclos->first();
        } else {
            // Si hay más de un rango, entra en el foreach
            $rangoSeleccionado = null;
            $rangos = [];

            foreach ($ciclos as $ciclo) {
                $rangos[$ciclo->rango]['calificaciones'][] = $ciclo->calificacion;
                $rangos[$ciclo->rango]['per_inicio'] = $ciclo->per_inicio;
            }
            
            foreach ($rangos as $rango => $datos) {
                $numReprobadas = collect($datos['calificaciones'])->filter(function ($calificacion) {
                    return $calificacion < 60;
                })->count();
                //dd($rango, $numReprobadas);

                if($numReprobadas > 3){
                    continue;
                }

                if ($numReprobadas < 3) {
                    if (is_null($rangoSeleccionado) || $datos['per_inicio'] < $rangoSeleccionado['per_inicio']) {
                        $rangoSeleccionado = ['rango' => $rango, 'per_inicio' => $datos['per_inicio']];
                    }
                } elseif (is_null($rangoSeleccionado) || $numReprobadas < $rangos[$rangoSeleccionado['rango']]['numReprobadas']) {
                    $rangoSeleccionado = ['rango' => $rango, 'per_inicio' => $datos['per_inicio'], 'numReprobadas' => $numReprobadas];
                }
            }
        }
            */

        //dd($ciclo->toSql(), $ciclo->getBindings());

        //dd($rangoSeleccionado);
        $buscar_excepciones_ciclos = ExcepcionCertificadosModel::join('cat_ciclos_esc', 'esc_excepciones_certificados.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
            ->select('rango')
            ->where('alumno_id', $alumno_id)
            ->where('periodo', $periodo)
            ->first();
        if ($buscar_excepciones_ciclos != null) {
            //dd($buscar_excepciones_ciclos);
            $nombre_ciclo = $buscar_excepciones_ciclos->rango;
        } else {
            $nombre_ciclo = $ciclo->rango;
        }

        return $nombre_ciclo;
    }

}