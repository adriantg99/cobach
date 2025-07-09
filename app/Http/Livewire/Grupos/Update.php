<?php

namespace App\Http\Livewire\Grupos;

use App\Models\Catalogos\AulaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Illuminate\Http\Request;


class Update extends Component
{
    public $cantidad_grupos;
    public $cve_turno;
    public $Plantel;
    public $cve_plantel;
    public $semestre;
    public $cve_ciclo;
    public $cve_periodo;
    public $capacidad_grupo;
    public $id_grupos;
    public $datos;
    public $nombre_grupo;
    public $descripcion_grupo;
    public $aula_grupo;
    public $aula;
    public $mensaje;
    public $gpo_base;

    public function mount()
    {
        if($this->id_grupos){
            $grupo = GruposModel::find($this->id_grupos);
            $this->datos = $grupo;
            $this->cve_turno = $grupo->turno_id;
            $this->cve_plantel = $grupo->plantel_id;
            $this->cve_ciclo = $grupo->ciclo_esc_id;
            $this->cve_periodo = substr($grupo->nombre,0,1);
            $this->capacidad_grupo = $grupo->capacidad;
            $this->nombre_grupo = $grupo->nombre;
            $this->descripcion_grupo = $grupo->descripcion;
            $this->aula_grupo = $grupo->aula_id;
            $this->gpo_base = $grupo->gpo_base;
        }
        $this->Grupo = GruposModel::where("id", "=", $this->id_grupos)->first();
        $this->Plantel = PlantelesModel::select('nombre', 'id', 'abreviatura')->get();
        $this->Ciclos = CicloEscModel::orderBy('id', 'desc')->get();
        $this->aula = AulaModel::where('plantel_id', $grupo->plantel_id)->get();
        $this->datos = $this->id_grupos;
    }

    public function actualizar()
    {
        $rules = [
            'cve_turno' => 'required|max:100',
            'cve_plantel' => 'required|numeric',
            'cve_ciclo' => 'required|numeric',
            'cve_periodo' => 'required|max:10',
            'capacidad_grupo' => 'nullable|numeric',
            'nombre_grupo'        => 'required|max:255',
            'descripcion_grupo' => 'required|max:255',
        ];
        
            $this->validate($rules);

            if (Auth()->user()->hasPermissionTo('grupos-editar')) {
                $actualizar = GruposModel::find($this->id_grupos);
    
    
                $actualizar = [
                    'turno_id' => $this->cve_turno,
                    'plantel_id' => $this->cve_plantel,
                    'ciclo_esc_id' => $this->cve_ciclo,
                    'periodo' => $this->cve_periodo,
                    'capacidad' => $this->capacidad_grupo,
                    'nombre' => $this->nombre_grupo,
                    'descripcion' => $this->descripcion_grupo,
                    'aula_id' =>  $this->aula_grupo,
                    'gpo_base'  =>  $this->gpo_base,
                ];
                
                $actualizar_registro = GruposModel::find($this->id_grupos);
                $actualizar_registro->update($actualizar);
                
                redirect()->route('Grupos.crear.index')->with('success','Grupo actualizado');
    
            }
            else{
                $this->mensaje = "Favor de validar los permisos";
    
            }
        
            // Si la validación pasa, continúa con el procesamiento
            // ...
        
            // Después de procesar con éxito, puedes redirigir o realizar otras acciones
        
    }

    public function render()
    {
        return view('livewire.grupos.update');
    }
}
