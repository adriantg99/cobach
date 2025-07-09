<?php
// ANA MOLINA 02/08/2023
namespace App\Http\Livewire\Catalogos\Reglamentos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\ReglamentoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

//Para cargar los selects:
use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\PoliticaModel;
use App\Models\Catalogos\Reglamento_politicaModel;

class FormComponent extends Component
{
    public $reglamento_id;
    public $nombre;

    public $id_areaformacion_change=0;
    public $sel_idpolitica;
    public $sel_idpoliticaasi;

     //muestra politicas asignadas id
     public $polasiid=[];
    //muestra area formación asignadas id
    public $areaasiid=[];

    public function mount()
    {
        if($this->reglamento_id)
        {
            $reglamento = ReglamentoModel::find($this->reglamento_id);
            $this->nombre = $reglamento->nombre;

            //muestra politicas asignadas
            $politicas=Reglamento_politicaModel::where('id_reglamento',$this->reglamento_id)->get();
            if(isset($politicas))
                foreach ($politicas as $polidx)
                {
                    $politica=PoliticaModel::find($polidx->id_politica);


                    $this->polasiid[]=$politica->id;

                    $this->areaasiid[]=$politica->id_areaformacion;
                }
        }
    }

    public function guardar()
    {
        $rules=[
            'nombre'                    =>  'required|max:250',

        ];

        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

        //arreglo para ingresarlo a la tabla
        $data = [
            'nombre'                =>  $this->nombre,
        ];

        if($this->reglamento_id == null)
        {
           //Crear registro
            if(Auth()->user()->hasPermissionTo('reglamento-crear'))
            {
                ReglamentoModel::create($data);
                $reglamento= ReglamentoModel::latest('id')->first();

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Creó Reglamento id:'.$reglamento->id,
                ]);


                redirect()->route('catalogos.reglamentos.index')->with('success','Reglamento creado correctamente');
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

                redirect()->route('catalogos.reglamentos.index')->with('danger','No tiene los permisos necesarios');
            }
        }
        else
        {
            //Editar registro
            if(Auth()->user()->hasPermissionTo('reglamento-editar'))
            {
                $reglamento = ReglamentoModel::find($this->reglamento_id);
                $reglamento->update($data);

                BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    //'controller'    =>  'UserController',
                    'component'     =>  'FormComponent',
                    'function'  =>  'guardar',
                    'description'   =>  'Editó reglamento id:'.$reglamento->id,
                ]);

                redirect()->route('catalogos.reglamentos.index')->with('success','Reglamento editado correctamente');
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

                redirect()->route('catalogos.reglamentos.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }
    public function render()
    {
        //Para cargar los selects:

        //muestra politicas asignadas
        $politicasas=Reglamento_politicaModel::where('id_reglamento',$this->reglamento_id)->select('id_politica')
        ->get();

        $politicasasi=[];
        if(isset($politicasas))
        foreach ($politicasas as $polidx)
        {
            $politica=PoliticaModel::find($polidx->id_politica);
             $areaformacion=AreaFormacionModel::find($politica->id_areaformacion);

            $politicasasi[] = ['id_politica' => $politica->id, 'nombre' =>$politica->nombre, 'areaformacion' =>$areaformacion->nombre];

        }



        $areasformacion = AreaFormacionModel::select('id','nombre')->orderBy('nombre')
        ->get();

        $politicas = PoliticaModel::where('id_areaformacion', $this->id_areaformacion_change)->select('id','nombre')->orderBy('nombre')
        ->get();
        return view('livewire.catalogos.reglamentos.form-component', compact('areasformacion','politicas','politicasasi'));
    }
    public function changeEventareaformacion($idareaformacion)
    {

        $this->id_areaformacion_change=$idareaformacion;
         //Para cargar los selects:
         $politicas = PoliticaModel::where('id_areaformacion', $this->id_areaformacion_change)->select('id','nombre')->orderBy('nombre')
         ->get();
         $sel_idpolitica='';
    }
    public function changeEvent($idpolitica)
    {

        $this->sel_idpolitica=$idpolitica;

    }
    public function changeEventasi($idpolitica)
    {

        $this->sel_idpoliticaasi=$idpolitica;

    }
    public function asignar()
    {
         $pols = collect($this->polasiid);
        if($pols->contains($this->sel_idpolitica))
        {
            $this->sel_idpolitica='';
            session()->flash('message', 'Política asignada!');
            $this->dispatchBrowserEvent('alert',
            ['type' => 'info',  'message' => 'Política asignada!']);
        }
        else
        {
            $ares= collect($this->areaasiid);
            if($ares->contains($this->id_areaformacion_change))
            {
                $this->sel_idpolitica='';
                session()->flash('message', 'Area de formación asignada!');
                $this->dispatchBrowserEvent('alert',
                ['type' => 'info',  'message' => 'Area de formación asignada!']);
            }
            else
            {
                $politica=PoliticaModel::find($this->sel_idpolitica);
                $this->polasiid[]=$this->sel_idpolitica;
                $this->areaasiid[]=$politica->id_areaformacion;

                //inserta en politicas
                $datapol = [
                    'id_politica'=>$this->sel_idpolitica,
                    'id_reglamento'=>$this->reglamento_id
                                ];
                Reglamento_politicaModel::create($datapol);

            }
        }

    }
    public function quitar()
    {
       $index = array_search($this->sel_idpoliticaasi, $this->polasiid); // $key = 2;

       Reglamento_politicaModel::where('id_politica',$this->sel_idpoliticaasi)->where('id_reglamento',$this->reglamento_id)->delete();

       unset($this->polasiid[$index]);
       $this->polasiid = array_values($this->polasiid);

       unset($this->areaasiid[$index]);
       $this->areaasiid = array_values($this->areaasiid);
    }

}


