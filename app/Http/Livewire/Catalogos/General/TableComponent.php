<?php
// ANA MOLINA 12/05/2024
namespace App\Http\Livewire\Catalogos\General;
use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\GeneralModel;
use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $nombre;
    protected $paginationTheme = 'bootstrap';

    //listado de efirma
    public $efirmalst=[];
    public $bandera=true;
    public $id_check=0;
    public function render()
    {
        if($this->nombre == null)
        {
           $datosper = GeneralModel::get();
           $count_datosper =GeneralModel::count();
        }
        else
        {
            $datosper = GeneralModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')->get();
            $count_datosper = GeneralModel::where('nombre', 'LIKE', '%'.$this->nombre.'%')
            ->count();
        }
        if(isset($datosper))
        {
            if($this->bandera)
            {
                $this->efirmalst=[];
                foreach ($datosper as $dar)
                {
                    $this->efirmalst[] = ['id' => $dar->id, 'nombre' =>$dar->nombre, 'fechainicio' =>$dar->fechainicio, 'fechafinal' =>$dar->fechafinal, 'directorgeneral' =>$dar->directorgeneral];
                    if ($dar->directorgeneral)
                        $this->id_check=$dar->id;
                }
            }
            $this->bandera=true;
        }
        return view('livewire.catalogos.general.table-component', compact( 'count_datosper'));
    }
    function processMark($index,$id)
    {
        $this->bandera=false;
        if($this->efirmalst[$index]['directorgeneral'])
        {
            $this->id_check=$id;
            $contar=0;
            foreach ($this->efirmalst as $var) {
                if ($contar!=$index)
                    $this->efirmalst[$contar]['directorgeneral']=false;
                $contar++;

            }

        }
        else
        $this->id_check=0;
    }
    public function guardar()
    {
       $user_id= Auth()->user()->id;

       $general = GeneralModel::where('directorgeneral',true);
       $data = [
           'user_modif'              =>  $user_id,
           'directorgeneral'         => 0
       ];
       $general->update($data);

       if( $this->id_check!=0)
       {
            $data = [
                'user_modif'              =>  $user_id,
                'directorgeneral'         => 1
            ];
            $general = GeneralModel::find($this->id_check);
            $general->update($data);
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
            'description'   =>  'Editó director general id:'.$user_id,
        ]);

        return redirect()->route('catalogos.general.configura')->with('success','Datos guardados correctamente!');

    }
}
