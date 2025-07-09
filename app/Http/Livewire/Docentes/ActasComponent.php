<?php

namespace App\Http\Livewire\Docentes;

use App\Models\Administracion\BitacoraModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Docentes\ActasModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class ActasComponent extends Component
{
    public $Ciclos, $plantel, $select_plantel, $ciclos_escolares, $actas, $acta, $plantel_abrev, $select_curso, $Curso;
    protected $listeners = ['borrar_contador', 'buscar_acta', 'modificar_acta'];
    public function render()
    {
        if ($this->select_plantel != null) {
            $this->Curso = CursosModel::select('esc_curso.id', 'esc_curso.nombre', 'esc_grupo.descripcion as descripcion_grupo', 'esc_grupo.turno_id')
                ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_curso.grupo_id')
                ->where('esc_curso.docente_id', $this->docente->id)
                ->where('esc_grupo.plantel_id', $this->select_plantel)
                ->orderBy('esc_grupo.periodo')
                ->get();
        }
        return view('livewire.docentes.actas-component');
    }
    public function mount(){
        $this->plantel = PlantelesModel::select('cat_plantel.nombre', 'cat_plantel.id', 'cat_plantel.abreviatura')
        ->join('emp_perfil_plantele', 'emp_perfil_plantele.plantel_id', '=', 'cat_plantel.id')
        ->join('emp_perfil', 'emp_perfil.id', '=', 'emp_perfil_plantele.perfil_id')
        ->where('user_id', Auth()->user()->id)
        ->get();

        $this->docente = PerfilModel::where('user_id', Auth()->user()->id)->first();
        $this->Curso = collect();

    }
    public function borrar_contador(){
        $this->actas = collect();
    }

    public function modificar_acta($acta_id, $nueva_calif, $nuevas_faltas, $motivo){        
        
        $acta = ActasModel::find($acta_id);
        $acta->nueva_calif = $nueva_calif;
        $acta->nueva_falta = $nuevas_faltas;
        $acta->motivo = $motivo;
        $acta->estado = 1;
        $acta->observaciones  = null;
        if($acta->save()){
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'Actas especiales',
                //'component'     =>  'FormComponent',
                'function' => 'Se modifico un acta especial',
                'description' => 'Se modifico el acta ' . $acta->id. 'Para nueva revisión despues del rechazo',
            ]);

            $this->emit('solicitudActaCompleta', '1');
        }else{
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'Actas especiales',
                //'component'     =>  'FormComponent',
                'function' => 'Se intento modificar el acta especial',
                'description' => 'Se intento modificar el acta ' . $acta->id,
            ]);
            $this->emit('solicitudActaCompleta', '0');

        }
        
    }

    public function buscar_acta($acta_id){
        $datos_acta = ActasModel::find($acta_id);

        $this->emit('acta_encontrada', $datos_acta);
        //dd($datos_acta);
    }

    public function realizarBusqueda(){
        
        $this->actas = ActasModel::
        join('alu_alumno', 'esc_actas.alumno_id', '=', 'alu_alumno.id')
        ->leftjoin('esc_calificacion', function ($join) {
            $join->on('esc_calificacion.id', '=', 'esc_actas.calificacion_id');
        })
        ->select(
            DB::raw("CONCAT(alu_alumno.apellidos, ' ', alu_alumno.nombre) as alumno"), 'noexpediente',
            'esc_calificacion.calificacion_tipo', 'esc_actas.estado', 'esc_actas.observaciones',
            'esc_calificacion.calificacion', 'esc_calificacion.faltas', 'esc_actas.id'
        )
        ->where('docente_id', Auth()->user()->id)
        ->where('esc_actas.curso_id', $this->select_curso)
        ->orderBy('estado')
        ->orderBy('alumno')
        ->orderBy('calificacion_tipo')
        ->get();
        if (count($this->actas) == 0) {
            $this->dispatchBrowserEvent('funcion_listener');
            //$this->emit('sin_actas');
        }
    }

}
