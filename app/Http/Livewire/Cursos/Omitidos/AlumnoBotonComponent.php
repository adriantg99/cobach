<?php

namespace App\Http\Livewire\Cursos\Omitidos;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Cursos\CursosModel;
use App\Models\Cursos\CursosOmitidosModel;
use App\Models\Administracion\BitacoraModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AlumnoBotonComponent extends Component
{
    public $alumno_id;
    public $grupo_id;

    protected $listeners = ['muestra-modal' => 'toggleModal', 'quita_asign' => 'quitar_curso'];

    public function toggleModal($alumno_id,$grupo_id)
    {
        $this->alumno_id = $alumno_id;
        $this->grupo_id = $grupo_id;
        
        $this->dispatchBrowserEvent('show-modal', ['alumno_id' => $alumno_id]);

    }

    public function quitar_curso($asign, $alumno_id, $curso_id)
    {   
        $alumno = AlumnoModel::find($this->alumno_id);
        //dd('alumno:'.$alumno_id." - asign:".$asign);
        if($alumno_id == $alumno->noexpediente)
        {
            $curso = CursosModel::find($curso_id);
            //dd('sip alumno:'.$alumno->noexpediente." - asign:".$asign." = ".$curso->asignatura->nombre);

            $sql = "DELETE FROM esc_calificacion WHERE ((alumno_id = ".$alumno->id.") AND (curso_id = ".$curso->id."))";
            //elimina las calificaciones asociadas al alumno-curso
            $set = DB::select($sql);

            //agregar el curso a los cursos omitidos
            $data = [
                'curso_id'  => $curso->id,
                'alumno_id' => $alumno->id,
                'motivo'    => 'RECURSAMIENTO', 
            ];
            $set = CursosOmitidosModel::create($data);

            $this->cerrar_modal();
            //Notificar
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AlumnoBotonComponent',
                //'component'     =>  'FormComponent',
                'function'  =>  'quitar_curso',
                'description'   =>  'Seleccionó desde el mantenimiento de cursos eliminar el curso: '.$curso->id.', al alumno:'.$alumno->id,
            ]);
            $this->dispatchBrowserEvent('notificar_curso_omitido');
        }
        else 
        {
            dd('Error! alumno:'.$alumno->noexpediente." - asign:".$asign);
        }

    }

    public function cerrar_modal()
    {
        $this->dispatchBrowserEvent('hide-modal');
    }

    public function render()
    {
        $alumno = AlumnoModel::find($this->alumno_id);
        if($alumno)
        {
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'AlumnoBotonComponent',
                //'component'     =>  'FormComponent',
                'function'  =>  'render',
                'description'   =>  'Seleccionó ver cursoa del alumno:'.$alumno->id,
            ]);
        }
        

        return view('livewire.cursos.omitidos.alumno-boton-component',compact('alumno'));
    }
}
