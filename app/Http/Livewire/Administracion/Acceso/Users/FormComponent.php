<?php

namespace App\Http\Livewire\Administracion\Acceso\Users;

use App\Models\Administracion\BitacoraModel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class FormComponent extends Component
{
    public $user_id;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $rols = [];
    public $cont_rols;

    public $rules =  [
        'name' => 'required',
        'cont_rols' =>  'integer|min:1',
        //'email' => 'required|email|unique:users,email',
        //'password' => 'required|same:password_confirmation|min:8',
    ];

    public function mount()
    {

        if($this->user_id)
        {
            //Editando usuario
            $user = User::find($this->user_id);
            $this->name = $user->name;
            $this->email = $user->email;
            //$this->password = $user->password;


            $this->rules += [
                                'email' => 'required|email|unique:users,email,'.$this->user_id,
                                //'password' => 'same:password_confirmation|min:8',
                            ];

            //Cargar roles del usuario
            $roles = Role::get();
            foreach($roles as $r)
            {
                if($user->hasRole($r->name))
                {
                    array_push($this->rols,$r->id);
                }
            }
        }
        else
        {

            //Creando Usuario
            $this->rules += [
                                'email' => 'required|email|unique:users,email',
                                'password' => 'required|same:password_confirmation|min:8',
                            ];
        }
        //dd($this->rules);
    }

    public function guardar()
    {
        if($this->password)
        {
            $this->rules += [
                                'password' => 'same:password_confirmation|min:8',
                            ];
        }

        $this->validate();

        $data = [
            'name'              =>  $this->name,
            'email'             =>  $this->email,
            'password'          =>  Hash::make($this->password),
        ];

        if($this->user_id)
        {
            //Editando
            if((Auth()->user()->hasPermissionTo('user-editar')) or (Auth()->user()->id == 1))
            {
                $list = [];
                foreach($this->rols as $r)
                {
                    //dd($r);
                    $rol_elegido = Role::find($r);
                    array_push($list,$rol_elegido->name);
                }
                //dd(json_encode($list));

                $user = User::find($this->user_id);
                $user->name = $this->name;
                $user->email = $this->email;
                if($this->password)
                {
                    $user->password = Hash::make($this->password);
                }
                //if(Auth()->user()->hasPermissionTo('user-editar'))
                $user->save();

                $user->syncRoles($list);

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Edito user id:'.$user->id,
                ]);

                redirect()->route('user.index')->with('success','Usuario editado correctamente');
            }
            else
            {
                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Usuario sin permisos',
                ]);

                redirect()->route('user.index')->with('danger','No tiene los permisos necesarios');
            }

        }
        else
        {
            //Creando
            if(Auth()->user()->hasPermissionTo('user-crear'))
            {
                $list = [];
                foreach($this->rols as $r)
                {
                    //dd($r);
                    $rol_elegido = Role::find($r);
                    array_push($list,$rol_elegido->name);
                }
                //dd(json_encode($list));

                User::create($data);
                $user = User::latest('id')->first();
                $user->syncRoles($list);

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creo user id:'.$user->id,
                ]);

                redirect()->route('user.index')->with('success','Usuario creado correctamente');
            }
            else
            {
                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Usuario sin permisos',
                ]);

                redirect()->route('user.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }

    public function render()
    {
        $this->cont_rols = count($this->rols);

        $roles = Role::get();
        return view('livewire.administracion.acceso.users.form-component', compact('roles'));
    }
}
