<?php
// ANA MOLINA 27/06/2023
namespace App\Http\Livewire\Catalogos\Areasformacion;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\AreaFormacionModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormComponent extends Component
{
    public $areaformacion_id;
    public $nombre;
    public function mount()
    {
        if($this->areaformacion_id)
        {
            $areaformacion = AreaFormacionModel::find($this->areaformacion_id);
            $this->nombre = $areaformacion->nombre;

        }
    }

    public function guardar()
    {
        $rules=[
            'nombre'                    =>  'required|max:100',

        ];

        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

        //arreglo para ingresarlo a la tabla
        $data = [
            'nombre'                =>  $this->nombre,
        ];

        if($this->areaformacion_id == null)
        {
           //Crear registro
            if(Auth()->user()->hasPermissionTo('areaformacion-crear'))
            {
                AreaFormacionModel::create($data);
                $areaformacion = AreaFormacionModel::latest('id')->first();

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creó área de formación id:'.$areaformacion->id,
                ]);


                redirect()->route('catalogos.areasformacion.index')->with('success','Área de Formación creado correctamente');
            }
            else
            {
                //No tiene los permisos necesarios

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

                redirect()->route('catalogos.areasformacion.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Editar registro
            if(Auth()->user()->hasPermissionTo('areaformacion-editar'))
            {
                $areaformacion = AreaFormacionModel::find($this->areaformacion_id);
                $areaformacion->update($data);

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Editó área de formación id:'.$areaformacion->id,
                ]);

                redirect()->route('catalogos.areasformacion.index')->with('success','Área de Formación editado correctamente');
            }
            else
            {
                //No tiene los permisos necesarios

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

                redirect()->route('catalogos.areasformacion.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }

}
