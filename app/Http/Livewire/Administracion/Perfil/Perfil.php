<?php

namespace App\Http\Livewire\Administracion\Perfil;

use App\Models\Administracion\PerfilModel;
use App\Models\Administracion\PerfilPlanteleModel;
use Livewire\Component;

class Perfil extends Component
{

    public $user_id;
    public $nombre;
    public $apellido1;
    public $apellido2;
    public $fecha_nac;
    public $expediente;
    public $correo_personal;
    public $telefono;
    public $num_planteles;

    protected $rules = [
        'nombre'            =>  'required|max:300',
        'apellido1'         =>  'required|max:300',
        'apellido2'         =>  'max:300',
        'fecha_nac'         =>  'required',
        'expediente'        =>  'required|digits:4|numeric',
        'correo_personal'   =>  'required|email',
        'telefono'          =>  'required|numeric|digits:10',
        'num_planteles'     =>  'required|numeric|min:1'
    ];

    public function mount()
    {
        //$user = User::find(Auth()->user()->id);
        //$this->user_id = $user->id;
        $perfil = PerfilModel::where('user_id',$this->user_id)->first();
        if($perfil)
        {
            $this->nombre = $perfil->nombre;
            $this->apellido1 = $perfil->apellido1;
            $this->apellido2 = $perfil->apellido2;
            $this->fecha_nac = $perfil->fecha_nac;
            $this->expediente = $perfil->expediente;
            $this->correo_personal = $perfil->correo_personal;
            $this->telefono = $perfil->telefono;
            $this->num_planteles = $perfil->num_planteles;
        }
    }

     public function guardar()
    {
        $this->validate();

        $data = [
                'user_id'           =>  $this->user_id,
                'nombre'            =>  $this->nombre,
                'apellido1'         =>  $this->apellido1,
                'apellido2'         =>  $this->apellido2,
                'fecha_nac'         =>  $this->fecha_nac,
                'expediente'        =>  $this->expediente,
                'correo_personal'   =>  $this->correo_personal,
                'telefono'          =>  $this->telefono,
                'num_planteles'     =>  $this->num_planteles,
            ];
        $perfil = PerfilModel::where('user_id',$this->user_id)->first();

        if($perfil)
        {
            $perfil->update($data);
        }
        else
        {
            PerfilModel::create($data);
            $perfil = PerfilModel::latest('id')->first();
        }

        //Ajustar el numero de planteles
        $perfil_planelees = PerfilPlanteleModel::where('perfil_id', $perfil->id)->get();
        //dd($perfil_planelees);
        if(count($perfil_planelees)>0)
        {
            //dd('hay planteles');
            $cuantos_borrar = count($perfil_planelees) - $this->num_planteles;
            if($cuantos_borrar > 0)
            {
                for($j = 1; $j <= $cuantos_borrar; $j++)
                {
                    $perfil_plantel = PerfilPlanteleModel::where('perfil_id', $perfil->id)->orderBy('id', 'desc')->first();
                    $perfil_plantel->delete();
                }
            }
        }

        return redirect()->route('perfil')->with('success','Perfil guardado correctamente');
    }


    public function render()
    {

        return view('livewire.administracion.perfil.perfil');
    }
}
