<?php

namespace App\Http\Livewire\Administracion\Acceso\Roles;

use App\Models\Administracion\BitacoraModel;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class FormComponent extends Component
{
    public $rol_id;
    public $name;
    public $permission = [];

    public $rules =  [
        'name' => 'required',
    ];

    public function mount()
    {
        $permissions = Permission::get();

        if($this->rol_id)
        {
            //Edita
            $rol = Role::find($this->rol_id);
            $this->name = $rol->name;

            foreach($permissions as $p)
            {
                if($rol->hasPermissionTo($p->name))
                {
                    $this->permission += [$p->id => true];
                }
                else
                {
                    $this->permission += [$p->id => false];
                }
            }
        }
        else
        {
            //crear
            foreach($permissions as $p)
            {
                $this->permission += [$p->id => false];
            }
        }

    }

    public function guardar()
    {
        $this->validate();

        if($this->rol_id)
        {
            //Edita
            if(Auth()->user()->hasPermissionTo('rol-editar'))
            {
                $rol = Role::find($this->rol_id);
                $rol->name = $this->name;
                $rol->save();

                foreach($this->permission as $id => $valor)
                {
                    $permiso = Permission::find($id);
                    if($valor)
                    {
                        $rol->givePermissionTo($permiso->name);
                    }
                    else
                    {
                        $rol->revokePermissionTo($permiso->name);
                    }
                }

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Edito rol id:'.$rol->id,
                ]);

                redirect()->route('rol.index')->with('success','Rol editado correctamente');
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

                redirect()->route('rol.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Crea
            if((Auth()->user()->hasPermissionTo('rol-crear')) OR (Auth()->user()->id == 1))
            {
                $role = Role::create(['name'     =>     $this->name]);
                foreach($this->permission as $id => $valor)
                {
                    if($valor)
                    {
                        $permiso = Permission::find($id);
                        $role->givePermissionTo($permiso->name);
                    }
                }

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creo rol id:'.$role->id,
                ]);

                redirect()->route('rol.index')->with('success','Rol creado correctamente');
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

                redirect()->route('rol.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }

    public function render()
    {
        $permissions = Permission::get();
        return view('livewire.administracion.acceso.roles.form-component', compact('permissions'));
    }
}
