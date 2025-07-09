<?php
// ANA MOLINA 06/07/2023
namespace App\Http\Livewire\Catalogos\Politicas;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PoliticaModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

//Para cargar los selects:
use App\Models\Catalogos\AreaFormacionModel;
use App\Models\Catalogos\Politica_variabletipoModel;
use App\Models\Catalogos\Politica_variableModel;
use App\Models\Catalogos\Politica_variableperiodoModel;
use App\Models\Catalogos\Politica_variableletraModel;

use App\Models\Catalogos\Politica_variableletradetModel;
class FormulaFormComponent extends Component
{
    public $politica_id;
    public $id_areaformacion;
    public $id_variabletipo;
    public $nombre;
    public $descripcion;
    public $formula;
    public $calificacionminima;

    public $areaformacion;
    public $variabletipo;
    public $esregularizacion;
    public $eslimite;
    //listado de variables por política
    public $variablesPolitica=[];

    public function mount()
    {
        if($this->politica_id)
        {
            $politica = PoliticaModel::find($this->politica_id);

            $this->nombre = $politica->nombre;
            $this->id_areaformacion = $politica->id_areaformacion;
            $this->id_variabletipo =$politica->id_variabletipo;
            $this->descripcion = $politica->descripcion;
            $this->formula = $politica->formula;
            $this->calificacionminima = $politica->calificacionminima;

            $this->areaformacion = AreaFormacionModel::find($this->id_areaformacion)->nombre;

            $this->variabletipo = Politica_variabletipoModel::find($this->id_variabletipo)->nombre;

           //muestra variables
            $variables=Politica_variableModel::where('id_politica',$this->politica_id)->get();
             if(isset($variables))
                foreach ($variables as $varper)
                {
                    $variable=Politica_variableperiodoModel::find($varper->id_variableperiodo);

                    $this->variablesPolitica[] = ['id_variableperiodo' => $varper->id_variableperiodo, 'nombre' =>$variable->nombre];

                    if ($varper->esregularizacion==1)
                        $this->esregularizacion=$varper->id_variableperiodo;
                    if ($varper->eslimite==1)
                        $this->eslimite=$varper->id_variableperiodo;

                }

         }
    }

    public function guardar()
    {
        $rules=[
            //'abreviatura'               =>  'required|max:10', //Validacion para tener abreviatura única variable dependiendo de edicion o de crear
           'formula'                 =>  'required',
            'calificacionminima'                      =>  'required'


        ];


        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

               //arreglo para ingresarlo a la tabla

        $data = [
              'formula'             =>  $this->formula,
            'calificacionminima'                  =>  $this->calificacionminima

        ];

        $datareg = [
            'esregularizacion'             =>  1

        ];
        $datalim = [
            'eslimite'             =>  1

  ];

        //Editar registro
        if(Auth()->user()->hasPermissionTo('politica-editar'))
        {
            $politica = PoliticaModel::find($this->politica_id);
            $politica->update($data);

            Politica_variableModel::where('id_politica',$this->politica_id)->update(['esregularizacion'=>0]);
            Politica_variableModel::where('id_politica',$this->politica_id)->update(['eslimite'=>0]);

            $variableReg=Politica_variableModel::where('id_politica',$this->politica_id)->where('id_variableperiodo',$this->esregularizacion)->first();
            $variableReg->update($datareg);

            $variableLim=Politica_variableModel::where('id_politica',$this->politica_id)->where('id_variableperiodo',$this->eslimite)->first();
            $variableLim->update($datalim);

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component'     =>  'FormComponent',
                'function'  =>  'guardar',
                'description'   =>  'Edito politica id:'.$politica->id,
            ]);
            redirect()->route('catalogos.politicas.index')->with('success','Politica editado correctamente');
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
            redirect()->route('catalogos.politicas.index')->with('danger','No tiene los permisos necesarios');
        }

    }
    public function render()
    {
        //Para cargar los selects:

        return view('livewire.catalogos.politicas.formula-form-component');
    }

}
