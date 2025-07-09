<?php

namespace App\Http\Livewire\Cursos\Horarios;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\HoraPlantelModel;
use App\Models\Cursos\CursoHoraModel;
use App\Models\Cursos\CursosModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CursoBottonHorarioComponent extends Component
{
    public $curso_id;
    public $horas_semana;
    public $prueba;
    public $hora_id=[];
    public $día=[];
    public $horas_marcadas;

    protected $listeners = ['muestra-modal' => 'toggleModal', 'guarda_hora', 'cambio_horas_semana'];

    public function toggleModal($curso_id)
    {
        $this->curso_id = $curso_id;

        $this->dispatchBrowserEvent('show-modal', ['curso_id' => $curso_id]);

    }

    public function guarda_hora($objeto)
    {   
        $arreglo_dias = json_decode($objeto);
        //dd($arreglo_dias->datos); 
        
        if(Auth()->user()->can('horario-crear'))
        {
            //Borra el horario anterior antes de guardar el nuevo
            DB::select('DELETE FROM esc_curso_hora WHERE esc_curso_hora.curso_id = '.$this->curso_id);

            foreach($arreglo_dias->datos as $d)
            {
                if($d!=null) //Ignora los registros nulos (cuando borra una seleccion antes de guardar)
                {
                    $data = [
                        'curso_id'      =>  $d->curso_id,
                        'dia_semana'    =>  $d->dia,
                        'hora_plantel_id'   =>  $d->hora_id,
                    ];
                    CursoHoraModel::create($data);
                }
            }
        }
        $this->cerrar_modal();
        $curso = CursosModel::find($this->curso_id);
        $this->dispatchBrowserEvent('event_guardar_alert', [
            'grupo' => $curso->grupo->nombre.$curso->grupo->turno->abreviatura,
            'asignatura'    =>  $curso->asignatura->nombre,
            'curso_id'  =>  $curso->id,
        ]);
        //BItacora
        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            //'controller'    =>  'UserController',
            'component' => 'CursoBottonHorarioComponent',
            'function' => 'guarda_hora',
            'description' => 'Se guardó el horario del curso :'.$curso->id,
        ]);
        
        //HAcer un emit para dar refesh al render del listado de cursos
        $this->emitTo('cursos.actualiza-cursos-gpo-component','actualiza_listado');
    }

    public function cerrar_modal()
    {
        $this->dispatchBrowserEvent('hide-modal');
    }

    public function cambio_horas_semana()
    {
        if($this->curso_id != null)
        {
            $curso = CursosModel::find($this->curso_id);
            if($curso->horas_semana <> $this->horas_semana)
            {
                $curso->horas_semana = $this->horas_semana;
                $curso->update();
                //Aqui borra los horaris grabados en el curso...
                $this->limpiar();
            }
            $this->cerrar_modal();
        }
    }

    
    
    public function limpiar()
    {   
        $curso = CursosModel::find($this->curso_id);
        if(Auth()->user()->can('horario-borrar'))
        {
            DB::select('DELETE FROM esc_curso_hora WHERE esc_curso_hora.curso_id = '.$this->curso_id);
        }
        $this->cerrar_modal();
        $this->dispatchBrowserEvent('event_limpiar_alert', [
            'grupo' => $curso->grupo->nombre.$curso->grupo->turno->abreviatura,
            'asignatura'    =>  $curso->asignatura->nombre,
            'curso_id'  =>  $curso->id,
        ]);
        //BItacora
        BitacoraModel::create([
            'user_id' => Auth()->user()->id,
            'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path' => $_SERVER["REQUEST_URI"],
            'method' => $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            //'controller'    =>  'UserController',
            'component' => 'CursoBottonHorarioComponent',
            'function' => 'Limpiar',
            'description' => 'Se limpió el horario del curso :'.$curso->id,
        ]);

        //HAcer un emit para dar refesh al render del listado de cursos
        $this->emitTo('cursos.actualiza-cursos-gpo-component','actualiza_listado');
    }
    
    public function render()
    {
        if($this->curso_id == null)
        {
            $curso = $horas = null;
        }
        else
        {
            $curso = CursosModel::find($this->curso_id);
            if($curso->horas_semana == null)
            {
                $curso->horas_semana = $curso->asignatura->horas_semana;
                $curso->update();
                $this->horas_semana = $curso->horas_semana;
            }
            else
            {
                $this->horas_semana = $curso->horas_semana;
            }
            $horas = HoraPlantelModel::where('plantel_id',$curso->grupo->plantel_id)->get();
            
            $horas_curso = CursoHoraModel::where('curso_id',$this->curso_id)->get();
           
            if(count($horas_curso)>0)
            {
                $this->horas_marcadas = 1;
            } else {
                 $this->horas_marcadas = 0;
            }
            
        }
        
        if($curso!=null)
        {
            //BItacora
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component' => 'CursoBottonHorarioComponent',
                'function' => 'render',
                'description' => 'Consulta el horario del curso :'.$curso->id,
            ]);
        }
        
        return view('livewire.cursos.horarios.curso-botton-horario-component', 
            compact('curso', 'horas')
            );
    }
}
