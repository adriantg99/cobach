<?php

namespace App\Http\Livewire\Certificados\Cambio;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Escolares\ExcepcionCertificadosModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class CiclosComponent extends Component
{
    public $alumno_id, $alumno, $ciclos_alumno, $ciclo_seleccionado, $semestre;

    public function render()
    {
        if ($this->alumno_id) {
            if ($this->alumno == null) {
                $this->alumno = AlumnoModel::find($this->alumno_id);
                //$this->alumno_id_ant = $this->alumno_id;
      
                //dd($this->ciclos_alumno);
            } else {
                $this->ciclos_alumno = null;
            }


        }

        if($this->alumno){
            
            $this->ciclos_alumno = DB::table('esc_calificacion')
            ->join('esc_curso', 'esc_calificacion.curso_id', '=', 'esc_curso.id')
            ->leftJoin('esc_grupo', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
            ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
            ->where('esc_calificacion.alumno_id', $this->alumno_id)
            ->where('esc_calificacion.calificacion_tipo_id', '<>', 0)
            ->distinct()
            ->orderBy('esc_grupo.ciclo_esc_id')
            ->select('esc_grupo.ciclo_esc_id', 'cat_ciclos_esc.nombre')
            ->get();
        }
        return view('livewire.certificados.cambio.ciclos-component');
    }



    public function guardar()
    {
        $buscar_valores = ExcepcionCertificadosModel::where('alumno_id', $this->alumno_id)->where('periodo', $this->semestre)->first();

        if($buscar_valores){
            $data = [
                'alumno_id' => $this->alumno_id,
                'periodo' => $this->semestre,
                'ciclo_esc_id' => $this->ciclo_seleccionado
            ];

            $buscar_valores->update($data);
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Datos actualizados correctamente correctamente']);

        }else{
            $data = [
                'alumno_id' => $this->alumno_id,
                'periodo' => $this->semestre,
                'ciclo_esc_id' => $this->ciclo_seleccionado
            ];
        
            // Guardar los datos
            ExcepcionCertificadosModel::create($data);
        
            // Limpiar los valores
            //$this->reset(['alumno_id', 'semestre', 'ciclo_seleccionado']);
        
            // Disparar evento para SweetAlert
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Datos guardados correctamente']);
        
            // Redireccionar a la misma página (opcional, ya que el reset podría ser suficiente)
            //return redirect()->to(url()->current());
        }
        
    
    }
}
