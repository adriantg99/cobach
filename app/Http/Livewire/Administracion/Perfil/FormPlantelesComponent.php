<?php

namespace App\Http\Livewire\Administracion\Perfil;

use App\Models\Administracion\PerfilModel;
use App\Models\Administracion\PerfilPlanteleModel;
use App\Models\Catalogos\PlantelesModel;
use Livewire\Component;

class FormPlantelesComponent extends Component
{
    public $user_id;
    public $perfil_id;
    public $num_planteles;

    public $mensaje = null;

    public $plantel_id_1;
    public $plantel_id_2;
    public $plantel_id_3;

    public function mount()
    {
        $perfil = PerfilModel::where('user_id', $this->user_id)->first();
        $this->perfil_id = $perfil->id;
        $this->num_planteles = $perfil->num_planteles;
        $perfil_planelees =  PerfilPlanteleModel::where('perfil_id',$this->perfil_id)->get();
        if(count($perfil_planelees)>0)
        {
            $i=0;
            foreach($perfil_planelees as $pp)
            {
                $i++;
                switch ($i) {
                    case 1:
                        $this->plantel_id_1 = $pp->plantel_id;
                        break;
                    case 2:
                        $this->plantel_id_2 = $pp->plantel_id;
                        break;
                    case 3:
                        $this->plantel_id_3 = $pp->plantel_id;
                        break;
                }
            }
        }
    }

    public function guardar()
    {
        //No existan nulos
        $existen_nulos = false;
        for($i=1; $i<=$this->num_planteles; $i++)
        {
            switch ($i) {
                case 1:
                    if($this->plantel_id_1 == null)
                    {
                        $existen_nulos = true;
                    }
                    break;
                case 2:
                    if($this->plantel_id_2 == null)
                    {
                        $existen_nulos = true;
                    }
                    break;
                case 3:
                    if($this->plantel_id_3 == null)
                    {
                        $existen_nulos = true;
                    }
                    break;
            }
        }

        //Validar si hay repetidos
        $hay_repetidos = false;
        if($this->num_planteles == 2)
        {
            if($this->plantel_id_1 == $this->plantel_id_2)
            {
                $hay_repetidos = true;
            }
        }
        if($this->num_planteles == 3)
        {
            if(($this->plantel_id_1 == $this->plantel_id_2) OR ($this->plantel_id_1==$this->plantel_id_3) OR ($this->plantel_id_2==$this->plantel_id_3))
            {
                $hay_repetidos = true;
            }
        }


        if($existen_nulos == false)
        {
            if($hay_repetidos == false)
            {
                //Si existen planteles asignados los borra
                $perfil_planelees =  PerfilPlanteleModel::where('perfil_id',$this->perfil_id)->get();
                if(count($perfil_planelees)>0)
                {
                    foreach($perfil_planelees as $pp)
                    {
                        $pp->delete();
                    }
                }
                //Aqui los guarda
                for($i=1; $i <= $this->num_planteles; $i++)
                {
                    switch ($i) {
                        case 1:
                            $data = [
                                'perfil_id'     => $this->perfil_id,
                                'plantel_id'    => $this->plantel_id_1,
                            ];
                            break;
                        case 2:
                            $data = [
                                'perfil_id'     => $this->perfil_id,
                                'plantel_id'    => $this->plantel_id_2,
                            ];
                            break;
                        case 3:
                            $data = [
                                'perfil_id'     => $this->perfil_id,
                                'plantel_id'    => $this->plantel_id_3,
                            ];
                            break;
                    }
                    PerfilPlanteleModel::create($data);
                }
                return redirect()->route('perfil')->with('success','Perfil guardado correctamente');
            }
            else
            {
                $this->mensaje = 'LOS PLANTELES SELECCIONADOS NO DEBEN SER IGUALES';
            }
        }
        else
        {
            $this->mensaje = 'DEBE SELECCIONAR TODOS LOS PLANTELES';
        }
    }

    public function render()
    {
        $planteles = PlantelesModel::get();

        return view('livewire.administracion.perfil.form-planteles-component', compact('planteles'));
    }
}
