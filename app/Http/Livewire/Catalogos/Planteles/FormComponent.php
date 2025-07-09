<?php

namespace App\Http\Livewire\Catalogos\Planteles;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\LocalidadesModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormComponent extends Component
{
    public $plantel_id;
    public $abreviatura;
    public $nombre;
    public $cct;
    public $cve_mun;
    public $cve_loc;
    public $domicilio;
    public $zona;
    public $coordenadas;

    public $plan_domicilio; //calle s, num
    public $plan_codigopostal;
    public $plan_colonia;
    public $plan_telefono;
    public $plan_email;
    public $plan_acceso;

    public $director;
    public $correo_director;
    public $telefono_director;

    public $subdirector;
    public $correo_subdirector;
    public $telefono_subdirector;

    public function mount()
    {
        if($this->plantel_id)
        {
            $plantel = PlantelesModel::find($this->plantel_id);
            $this->abreviatura = $plantel->abreviatura;
            $this->nombre = $plantel->nombre;
            $this->cct = $plantel->cct;
            $this->cve_mun = $plantel->cve_mun;
            $this->cve_loc = $plantel->cve_loc;
            $this->domicilio = $plantel->domicilio;
            $this->zona = $plantel->zona;
            $this->coordenadas = $plantel->coordenadas;

            $this->plan_domicilio = $plantel->plan_domicilio;
            $this->plan_colonia = $plantel->plan_colonia;
            $this->plan_telefono = $plantel->plan_telefono;
            $this->plan_codigopostal = $plantel->plan_codigopostal;
            $this->plan_email = $plantel->plan_email;
            $this->plan_acceso = $plantel->plan_acceso;

            $this->director = $plantel->director;
            $this->correo_director = $plantel->correo_director;
            $this->telefono_director = $plantel->telefono_director;
            $this->subdirector = $plantel->subdirector;
            $this->correo_subdirector = $plantel->correo_subdirector;
            $this->telefono_subdirector = $plantel->telefono_subdirector;
        }
    }

    public function guardar()
    {
        $rules=[
            //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
            'nombre'                    =>  'required|max:100',
            'cct'                       =>  'required|max:10',
            'cve_mun'                   =>  'required|max:10',
            'cve_loc'                   =>  'required|max:10',
            'domicilio'                 =>  'max:110',
            'zona'                      =>  'required|max:10',
            'coordenadas'               =>  'max:50',

            'plan_domicilio'            =>  'max:100',
            'plan_colonia'              =>  'max:20',
            'plan_telefono'             =>  'max:20',
            'plan_codigopostal'         =>  'max:20',
            'plan_email'                =>  'max:100',
            'plan_acceso'               =>  'max:2000',

            'director'                  =>  'max:100',
            'correo_director'           =>  'max:100',
            'telefono_director'         =>  'max:20',
            'subdirector'               =>  'max:100',
            'correo_subdirector'        =>  'max:100',
            'telefono_subdirector'      =>  'max:20',
        ];
        if($this->plantel_id)
        {
            //Regla plara editar verifica que el campo abreviaura sea unico ignorando el registro actual
            $rules += [
                'abreviatura'               =>  'required|max:10|unique:cat_plantel,abreviatura,'.$this->plantel_id,
            ];
        }
        else
        {
            //Regla para crear verifica que el dato abreviatura sea unico
            $rules += [
                'abreviatura'               =>  'required|max:10|unique:cat_plantel,abreviatura',
            ];
        }

        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

        $municipio = LocalidadesModel::where('cve_ent','26')
            ->where('cve_mun',$this->cve_mun)->first();
        $localidad = LocalidadesModel::where('cve_ent','26')
            ->where('cve_mun',$this->cve_mun)
            ->where('cve_loc',$this->cve_loc)->first();

        //arreglo para ingresarlo a la tabla
        $data = [
            'abreviatura'           =>  $this->abreviatura,
            'nombre'                =>  $this->nombre,
            'cct'                   =>  $this->cct,
            'cve_mun'               =>  $this->cve_mun,
            'municipio'             =>  $municipio->nom_mun,
            'cve_loc'               =>  $this->cve_loc,
            'localidad'             =>  $localidad->nom_loc,
            'domicilio'             =>  $this->domicilio,
            'zona'                  =>  $this->zona,
            'coordenadas'           =>  $this->coordenadas,

            'plan_domicilio'        =>  $this->plan_domicilio,
            'plan_colonia'          =>  $this->plan_colonia,
            'plan_telefono'         =>  $this->plan_telefono,
            'plan_codigopostal'     =>  $this->plan_codigopostal,
            'plan_email'            =>  $this->plan_email,
            'plan_acceso'           =>  $this->plan_acceso,

            'director'              =>  $this->director,
            'correo_director'       =>  $this->correo_director,
            'telefono_director'     =>  $this->telefono_director,
            'subdirector'           =>  $this->subdirector,
            'correo_subdirector'    =>  $this->correo_subdirector,
            'telefono_subdirector'  =>  $this->telefono_subdirector,
        ];

        if($this->plantel_id == null)
        {
            //Crear registro
            if(Auth()->user()->hasPermissionTo('plantel-crear'))
            {
                PlantelesModel::create($data);
                $plantel = PlantelesModel::latest('id')->first();

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creó plantel id:'.$plantel->id,
                ]);


                redirect()->route('catalogos.planteles.index')->with('success','Plantel creado correctamente');
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

                redirect()->route('catalogos.planteles.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Editar registro
            if(Auth()->user()->hasPermissionTo('plantel-editar'))
            {
                $plantel = PlantelesModel::find($this->plantel_id);
                $plantel->update($data);

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Editó plantel id:'.$plantel->id,
                ]);

                redirect()->route('catalogos.planteles.index')->with('success','Plantel editado correctamente');
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

                redirect()->route('catalogos.planteles.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }

    public function render()
    {
        //Para cargar los selects:
        $municipios = LocalidadesModel::select('cve_ent','cve_mun', 'nom_mun', DB::raw('count(id) as total'))
            ->groupBy('cve_ent', 'cve_mun', 'nom_mun')
            ->having('cve_ent', '=', '26')
            ->get();
        if($this->cve_mun <> null)
        {
            $localidades = LocalidadesModel::select('cve_ent', 'cve_mun', 'cve_loc', 'nom_loc', DB::raw('count(id) as total'))
                ->groupBy('cve_ent', 'cve_mun', 'cve_loc', 'nom_loc')
                ->having('cve_mun', '=', $this->cve_mun)
                ->having('cve_ent', '=', '26')
                ->get();
        }
        else
        {
            $localidades = null;
        }

        return view('livewire.catalogos.planteles.form-component', compact('municipios', 'localidades'));
    }
}
