<?php

namespace App\Http\Livewire\Cursos;

use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Escolares\BajasAlumnos;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use App\Exports\AlumnosExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Auth;


class ConsultaCursosGpoComponent extends Component
{
    public $ciclo_esc_id;
    public $plantel_id;
    public $gpo_id;
    public $bool_consultar_curso;
    public $bool_consultar_alumno;
    public $grupos;
    protected $listeners = ['borrar_alumno', 'boletas'];

    public $grupo_seleccionado_impresion;
    public $boton_presionado;



    //protected $listeners = ['postAdded'];

    //
    public function mount()
    {
        $this->bool_consultar_curso = false;
        $this->grupos = null;
        $this->bool_consultar_alumno = false;
    }

    public function limpiabusqueda()
    {
        return redirect()->route('cursos.consulta_cursos_gpo');
    }

    /*
        public function consultar_cursos()
        {
            $this->bool_consultar_curso = true;
            //$grupo = GruposModel::find($this->gpo_id);
            //$cursos = $grupo->cursos;
            //dd($cursos);
        }
    */

    public function borrar_alumno($id_alumno, $grupo_id, $motivo)
    {
        //if (Auth()->user()->hasPermissionTo('alumno-borrar')) {
        // Encuentra las referencias
        //dd($id_alumno);


        $grupo_alumno = GrupoAlumnoModel::where('grupo_id', $grupo_id)->where('alumno_id', $id_alumno)->get();
        // Elimina las referencias encontradas
        if ($grupo_alumno->isNotEmpty()) {
            //Borra calificaciones  asociadas al alumno
            foreach($grupo_alumno as $gpo_alumn)
            {
                $cursos = $gpo_alumn->grupo->cursos;
                if($cursos)
                {
                    foreach($cursos as $curso)
                    {
                        $calificacion =  CalificacionesModel::where('alumno_id',$id_alumno)
                            ->where('curso_id',$curso->id)->first();
                        
                        if($calificacion)
                        {
                            BitacoraModel::create([
                                'user_id' => Auth()->user()->id,
                                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                                'path' => $_SERVER["REQUEST_URI"],
                                'method' => $_SERVER['REQUEST_METHOD'],
                                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                                'controller' => 'MantenimientoController',
                                //'component'     =>  'FormComponent',
                                'function' => 'eliminar',
                                'description' => 'Eliminar alumno' . $id_alumno . ' asignatura:' . $curso->asignatura->nombre.' Calificacion:'.$calificacion->calificacion.$calificacion->calif,
                            ]);
                            $calificacion->delete();
                        }
                    }
                }
            }

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'MantenimientoController',
                //'component'     =>  'FormComponent',
                'function' => 'eliminar',
                'description' => 'Eliminar alumno' . $id_alumno . ' del grupo:' . $grupo_id,
            ]);
            $ciclo_actual = CicloEscModel::where('activo', '1')->first();
            
            DB::insert('INSERT INTO esc_bajas_alumnos (alumno_id, ciclo_esc_id, motivo, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())', [
                $id_alumno,
                $ciclo_actual->id,
                $motivo,
                auth()->user()->id
            ]);
            GrupoAlumnoModel::where('grupo_id', $grupo_id)->where('alumno_id', $id_alumno)->delete();
            return response()->json(['success' => true, 'message' => 'Referencias eliminadas correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No se encontraron referencias para eliminar.']);
        }
        /*} else {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para realizar esta acción.']);
        }*/

    }


    public function exportarExcel()
    {
        //sdd($this->grupo_seleccionado_impresion);
        $plantel = PlantelesModel::find($this->plantel_id);
        $grupo = $this->grupo_seleccionado_impresion->nombre;
        $turno = $this->grupo_seleccionado_impresion->turno_id;
        if ($turno == "1") {
            $turno_nombre = "Matutino";
        } elseif ($turno == "2") {
            $turno_nombre = "Vespertino";
        }



        //dd($this->alumnos_en_grupo_seleccionado);
        $alumnos = collect($this->alumnos_en_grupo_seleccionado);

        return Excel::download(new AlumnosExport($alumnos, $grupo, $turno_nombre, $plantel->nombre), $grupo . '_' . $turno_nombre . '_' . $plantel->nombre . '.xlsx');

    }




    public function render()
    {
        
        $grupos = null;
        $plantel = null;
        $ciclo_esc = null;
        $planteles = null;
        $ciclos_esc = null;
        $grupo_seleccionado = null;
        $alumnos = null;

        if ($this->bool_consultar_curso == false) {
            $planteles = obtenerPlanteles();
            $permisos = permisos();
            
            if($permisos != 0){
                $ciclos_esc = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre', 'activo')
                ->distinct()
                ->join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->orderBy('cat_ciclos_esc.id', 'desc')
                ->get();
            }
            else{
                $ciclos_esc = CicloEscModel::select('cat_ciclos_esc.id', 'cat_ciclos_esc.nombre', 'activo')
                ->distinct()
                ->join('esc_grupo', 'cat_ciclos_esc.id', '=', 'esc_grupo.ciclo_esc_id')
                ->orderBy('cat_ciclos_esc.id', 'desc')
                ->where('activo', '1')
                ->get();
            }
            
        } else {
            $grupo_seleccionado = GruposModel::find($this->gpo_id);
        }

        if ($this->bool_consultar_alumno == true) {
            $grupo_seleccionado = GruposModel::find($this->gpo_id);
            $this->grupo_seleccionado_impresion = clone $grupo_seleccionado;
            $alumnos = DB::table('esc_grupo_alumno')
                ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
                ->where('grupo_id', $this->gpo_id)
                ->orderBy('apellidos', 'asc')
                ->get();
            $this->alumnos_en_grupo_seleccionado = clone $alumnos;

        }

        if (($this->ciclo_esc_id != null) and ($this->plantel_id != null) and ($this->grupos == null)) {
            $this->grupos = GruposModel::where('plantel_id', $this->plantel_id)
                ->where('ciclo_esc_id', $this->ciclo_esc_id)->withCount('cursos') //->withCount('alumnos')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->orderBy('nombre')
                ->get();
            //dd($grupos);

            $this->dispatchBrowserEvent('cargar_select2_grupo');
        }
        $grupos = $this->grupos;
        //$plantel = obtenerPlanteles();
        if ($this->plantel_id != null) {
            $plantel = PlantelesModel::find($this->plantel_id);
        }
        if ($this->ciclo_esc_id != null) {
            $ciclo_esc = CicloEscModel::find($this->ciclo_esc_id);
        }




        return view('livewire.cursos.consulta-cursos-gpo-component', compact('planteles', 'ciclos_esc', 'grupos', 'plantel', 'ciclo_esc', 'grupo_seleccionado', 'alumnos'));
    }

    public function boletas($alumno = null)
    {
    
    }
}