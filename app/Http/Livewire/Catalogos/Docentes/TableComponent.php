<?php

namespace App\Http\Livewire\Catalogos\Docentes;

use App\Models\Administracion\PerfilModel;
use App\Models\Administracion\PerfilPlanteleModel;
use App\Models\Catalogos\DocentesCorreosModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class TableComponent extends Component
{
    public $plantel_id;
    public $nuevo_email;
    public $docente;  // Variable para almacenar el docente a editar

    protected $listeners = ['actualizar_docentes'];


    public function agregar()
    {
        $rules = [
            'nuevo_email'   => 'email|regex:/[^@ \t\r\n]+@bachilleresdesonora\.edu.mx/'
        ];

        $this->validate($rules);

        //Buscar el nuevo mail en usuarios si no existe agregarlo
        $user =  User::where('email', $this->nuevo_email)->first();
        if($user == null)
        {
            $data = [
                'name'              =>  'Usuario CE',
                    'email'             =>  $this->nuevo_email,
                    'email_verified_at' =>  date("Y-m-d H:i:s"),
                    'password'          =>  Hash::make('LTODO2023'),
                    'google_id'         =>  '',
                    'google_picture'    =>  '',
            ];
            $user = User::create($data);
        }

        //Buscar en perfiles si no existe crearlo
        $perfil = PerfilModel::where('user_id', $user->id)->first();
        if($perfil == null)
        {
            $data = [
                'user_id'           =>  $user->id,
                'nombre'            =>  'CE',
                'apellido1'         =>  $user->email,
                'apellido2'         =>  'CE AP2',
                'fecha_nac'         =>  date("Y-m-d"),
                'expediente'        =>  '0000',
                'correo_personal'   =>  '0000000000',
                'telefono'          =>  '0000000000',
                'num_planteles'     =>  1,
            ];
            $perfil = PerfilModel::create($data);
        }

        //Buscar si el perfil tiene asignado el plantel
        $perfil_plantel = PerfilPlanteleModel::where('perfil_id', $perfil->id)->where('plantel_id', $this->plantel_id)->first();
        //dd($perfil);
        if($perfil_plantel == null)
        { 
            $data = [
                'perfil_id' => $perfil->id,
                'plantel_id' => $this->plantel_id
            ];
            $perfil_plantel = PerfilPlanteleModel::create($data);
        }

        //Agregar a catdocentescorreos
        $docente_correo = DocentesCorreosModel::where('email',$user->email)->first();
        if($docente_correo == null)
        {
            $data = [
                'email' =>  $user->email,
            ];
            $docente_correo = DocentesCorreosModel::create($data);
        }

        return redirect()->route('adm.docentes')->with('success','Docente agregado correctamente');
    }

    public function render()
    {
        $plantel = PlantelesModel::find($this->plantel_id);

        $this->actualizar_docentes();
        //dd($docentes);

        return view('livewire.catalogos.docentes.table-component', compact('plantel'));
    }


    public function actualizar_docentes(){
        $sql = "SELECT cat_docentes_correos.email, emp_perfil.nombre, emp_perfil.apellido1, ";
        $sql = $sql."emp_perfil.apellido2, emp_perfil_plantele.plantel_id, users.name ";
        $sql = $sql."FROM cat_docentes_correos INNER JOIN users ON cat_docentes_correos.email = users.email ";
        $sql = $sql."INNER JOIN emp_perfil ON emp_perfil.user_id = users.id ";
        $sql = $sql."INNER JOIN emp_perfil_plantele ON emp_perfil_plantele.perfil_id = emp_perfil.id ";
        $sql = $sql."WHERE emp_perfil_plantele.plantel_id = ".$this->plantel_id." ";
        $sql = $sql."ORDER BY email";
        $sql = $sql."";

        $this->docentes = DB::select($sql);
    }
}
