<?php
// ANA MOLINA 27/06/2023
namespace App\Http\Livewire\Catalogos\General;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\GeneralModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

use App\EncryptDecrypt\Encrypt_Decrypt;
use App\PhpCfdi\Efirma;

class FormComponent extends Component
{
    use WithFileUploads;

    public $general_id=null;
    public $user_id;
    public $nombre;
    public $rfc;
    public $titulo;
    public $ciudad;
    public $efirma_nombre;
    public $efirma_password;
    public $efirma_file_certificate;
    public $efirma_file_key;
    public $fechainicio;
    public $fechafinal;
    public $directorgeneral=false;
    public $numcertificado;
    public $sellodigital;
    public $desde;
    public $hasta;

    // files
    public $efirma_file_certificate1=null;
    public $efirma_file_key1=null;
    public function mount()
    {
        $datosper = GeneralModel::where('user_id',Auth()->user()->id)->first();
         if($datosper!=null)
        {
            $this->general_id=$datosper->id;
            $this->user_id=$datosper->user_id;
            $this->nombre = $datosper->nombre;
            $this->rfc = $datosper->rfc;
            $this->titulo = $datosper->titulo;
            $this->ciudad = $datosper->ciudad;
            $this->efirma_nombre = $datosper->efirma_nombre;
            $this->efirma_password =Encrypt_Decrypt::decrypt( $datosper->efirma_password);
            $this->efirma_file_certificate = $datosper->efirma_file_certificate;
            $this->efirma_file_key = $datosper->efirma_file_key;
            $this->fechainicio = $datosper->fechainicio;
            $this->fechafinal = $datosper->fechafinal;
            $this->numcertificado= $datosper->numcertificado;
            $this->sellodigital= $datosper->sellodigital;
            $timed = strtotime($datosper->desde);
            $newd = date('d-m-Y',$timed);

            $timeh = strtotime($datosper->hasta);
            $newh= date('d-m-Y',$timeh);
            $this->desde=$newd;
            $this->hasta=$newh;
        }
    }

    public function guardar()
    {
        $rules=[
            'nombre'                    =>  'required|max:100',
            'rfc'                       =>  'required|max:20',
            'titulo'                    =>  'required|max:10',
            'ciudad'                    =>  'required|max:100',
            'efirma_nombre'           =>  'required|max:200',
            'efirma_password'           =>  'required|max:30',
            'efirma_file_certificate'   =>  'required|max:100',
            'efirma_file_key'           =>  'required|max:100',
            'fechainicio'               =>  'required',
            'numcertificado'            =>  'required|max:100',
            'sellodigital'             =>  'required|max:500',
            'desde'                    =>  'required',
            'hasta'                     =>  'required'
        ];
           //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $this->validate($rules);

        //arreglo para ingresarlo a la tabla
        $this->user_id= Auth()->user()->id;

        

        // if ($this->efirma_file_certificate1!=null)
        //     $this->efirma_file_certificate=$this->efirma_file_certificate1->getClientOriginalName();

        // if ($this->efirma_file_key1!=null)
        //     $this->efirma_file_key=$this->efirma_file_key1->getClientOriginalName();

        $timed = strtotime($this->desde);
        $newd = date('Y-m-d',$timed);

        $timeh = strtotime($this->hasta);
        $newh= date('Y-m-d',$timeh);


        $data = [

            'user_id'                 =>  $this->user_id,
            'nombre'                  =>  $this->nombre,
            'rfc'                     =>  $this->rfc,
            'titulo'                  =>  $this->titulo,
            'ciudad'                  =>  $this->ciudad,
            'efirma_nombre'            =>  $this->efirma_nombre,
            'efirma_password'         => Encrypt_Decrypt::encrypt( $this->efirma_password),
            'efirma_file_certificate' =>  $this->efirma_file_certificate,
            'efirma_file_key'         =>  $this->efirma_file_key,
            'fechainicio'             =>  $this->fechainicio,
            'fechafinal'              =>  $this->fechafinal,
            'user_modif'              => Auth()->user()->id,
            'directorgeneral'         => $this->directorgeneral,
            'numcertificado'         => $this->numcertificado,
            'sellodigital'         => $this->sellodigital,
            'desde'                =>$newd,
            'hasta'                  =>$newh

        ];

        if($this->general_id == null)
        {
           //Crear registro

            GeneralModel::create($data);
            $general = GeneralModel::latest('id')->first();
            $this->general_id=$general->id;
            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component'     =>  'FormComponent',
                'function'  =>  'guardar',
                'description'   =>  'Creó datos personales id:'. $this->user_id,
            ]);


        }
        else
        {
            //Editar registro

            $general = GeneralModel::find($this->general_id);
            $general->update($data);

            BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                //'controller'    =>  'UserController',
                'component'     =>  'FormComponent',
                'function'  =>  'guardar',
                'description'   =>  'Editó datos personales id:'.$this->user_id,
            ]);
        }

        //dd($this->efirma_file_certificate1);
    //originalName
    //filename
        if ($this->efirma_file_certificate1!=null)
            $this->efirma_file_certificate1->storeAs('efirma/efirma-'.$this->general_id,$this->efirma_file_certificate);
        if ($this->efirma_file_key1!=null)
            $this->efirma_file_key1->storeAs('efirma/efirma-'.$this->general_id,$this->efirma_file_key);

    //     $nombre_archivo = $_FILES['efirma_file_key']['name'];
    //     $tipo_archivo = $_FILES['efirma_file_key']['type'];

    //    valida_archivo($nombre_archivo,$tipo_archivo,".key");

    //    $nombre_archivo = $_FILES['efirma_file_certificate']['name'];
    //    $tipo_archivo = $_FILES['efirma_file_certificate']['type'];

    //   valida_archivo($nombre_archivo,$tipo_archivo,".cer");
        return redirect()->route('catalogos.general.datospersonales')->with('success','Datos personales guardados correctamente!');

    }
    public function valida_archivo($nombre_archivo,$tipo_archivo,$type)
    {
         //datos del arhivo

         //compruebo si las características del archivo son las que deseo
         if (!((strpos($tipo_archivo, $type) ))) {
             echo "La extensión de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .cer o .key<br><li>se permiten archivos de 100 Kb máximo.</td></tr></table>";
         }else{
             if (move_uploaded_file($_FILES['efirma_file_key']['tmp_name'],  $nombre_archivo)){
                     echo "El archivo ha sido cargado correctamente.";
             }else{
                     echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
             }
         }
    }
    public function validar()
    {
        if(!empty($this->efirma_password) && !empty($this->efirma_file_key1) && !empty($this->efirma_file_certificate1)){
            if ($this->efirma_file_certificate1!=null)
            $this->efirma_file_certificate=$this->efirma_file_certificate1->getClientOriginalName();

        if ($this->efirma_file_key1!=null)
            $this->efirma_file_key=$this->efirma_file_key1->getClientOriginalName();

        if ($this->efirma_file_certificate1!=null)
            $this->efirma_file_certificate1->storeAs('efirma/efirma-tmp',$this->efirma_file_certificate);
        if ($this->efirma_file_key1!=null)
            $this->efirma_file_key1->storeAs('efirma/efirma-tmp',$this->efirma_file_key);

            // dd($this->efirma_file_certificate1);
            // dd($this->efirma_file_key);
         $direct='efirma-tmp/';
        // dd($direct.$this->efirma_file_certificate);

        $csdarr=Efirma::get_certificado('Certificado',$direct.$this->efirma_file_certificate,$direct.$this->efirma_file_key ,$this->efirma_password);
        $this->nombre=$csdarr[3];
        $this->efirma_nombre=$csdarr[3];
        $this->rfc=$csdarr[2];
        $this->numcertificado=$csdarr[1];
        $this->sellodigital=$csdarr[0];
        $this->desde=$csdarr[4];
        $this->hasta=$csdarr[5];
        //dd($csdarr);
        }
        else{
            
        }
       
    }

}
