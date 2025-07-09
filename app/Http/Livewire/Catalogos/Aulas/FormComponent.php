<?php

namespace App\Http\Livewire\Catalogos\Aulas;

use App\Models\Catalogos\AulaModel;
use App\Models\Catalogos\Aula_condicionModel;
use App\Models\Catalogos\Aula_tipoModel;
use App\Models\Catalogos\PlantelesModel;
use Livewire\Component;

class FormComponent extends Component
{
    public $plantel_id;
    public $plantel_nombre;
    public $plantel_cct;

    public $aula_id;
    public $nombre;
    public $tipo_aula_id;
    public $condicion_aula_id;
    public $aula_activa = false;
    public $descripcion;

    public function mount()
    {
        $plantel = PlantelesModel::find($this->plantel_id);
        $this->plantel_nombre = $plantel->nombre;
        $this->plantel_cct = $plantel->cct;

        if($this->aula_id)
        {
            $aula = AulaModel::find($this->aula_id);
            $this->nombre = $aula->nombre;
            $this->tipo_aula_id = $aula->tipo_aula_id;
            $this->condicion_aula_id = $aula->condicion_aula_id;
            $this->aula_activa = $aula->aula_activa;
            $this->descripcion = $aula->descripcion;
        }
    }

    public function guardar()
    {
        $rules=[
            'nombre'            =>  'required|max:250',
            'tipo_aula_id'      =>  'required',
            'condicion_aula_id' =>  'required',
            'aula_activa'       =>  'required',
            'descripcion'       =>  'max:2000',
        ];

        $this->validate($rules);

        $data = [
            'plantel_id'        =>  $this->plantel_id,
            'nombre'            =>  $this->nombre,
            'tipo_aula_id'      =>  $this->tipo_aula_id,
            'condicion_aula_id' =>  $this->condicion_aula_id,
            'aula_activa'       =>  $this->aula_activa,
            'descripcion'       =>  $this->descripcion,
        ];
        
        if($this->aula_id)
        {
            $aula = AulaModel::find($this->aula_id);
            $aula->update($data);
            
            redirect()->route('catalogos.plantel.aulas',$this->plantel_id)->with('success','Aula editada correctamente');
        }
        else
        {
            //Crear
            AulaModel::create($data);
            
            redirect()->route('catalogos.plantel.aulas',$this->plantel_id)->with('success','Aula creada correctamente');
        }
    }
    
    public function render()
    {
        $aulas_tipo = Aula_tipoModel::get();
        //dd($aulas_tipo);
        $aulas_condicion = Aula_condicionModel::get();

        return view('livewire.catalogos.aulas.form-component', compact('aulas_tipo', 'aulas_condicion'));
    }
}
