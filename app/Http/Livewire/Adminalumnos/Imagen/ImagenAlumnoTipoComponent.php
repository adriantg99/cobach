<?php

namespace App\Http\Livewire\Adminalumnos\Imagen;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Administracion\BitacoraModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;


class ImagenAlumnoTipoComponent extends Component
{
    use WithFileUploads;

    public $tipo;
    public $alumno_id;
    public $alumno_id_ant;
    public $alumno;
    public $file;
    public $documento, $tipo_archivo;
    protected $listeners = ['borrar_foto'];


    //ACTUALIZA FOTOS TIPO DE ALUMNOS http://sce-cobach.test/adminalumnos/imagenalumno 

    public function subir_archivo()
    {
        $nombre = "";
        if ($this->tipo == "1") {
            $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'image|max:1024', // 1MB Max
            ];
            $nombre = "Foto_boleta";
        } elseif($this->tipo == "2") {
            $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:max_width=192,max_height=240',
            ];
            $nombre = "Foto_Certificado";
        }
        //Acta
        elseif($this->tipo == "4"){
            $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'required|file|mimes:pdf, jpg,jpeg,png|max:2048',
            ];
            $nombre = "Doc_Acta";
        }
        //Certificado
        elseif($this->tipo == "5"){
            $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ];
            $nombre = "Doc_Certificado";
        }
        //CURP
        elseif($this->tipo == "6"){
            $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ];
            $nombre = "Doc_Curp";
        }
        elseif($this->tipo == "7"){
            $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ];
            $nombre = "Doc_Revalidacion";

        }
        elseif ($this->tipo == "8") {
               $rules = [
                'alumno_id' => 'required',
                'tipo' => 'required',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ];
            $nombre = "Doc_Equivalencia";

        }
      

        $this->validate($rules);

        //dd(filesize($this->file->getRealPath()));
        $fp = fopen($this->file->getRealPath(), 'r+b');
        $data_f = fread($fp, filesize($this->file->getRealPath()));
        fclose($fp);
        //dd($data_f);
        //$data_f2=addslashes($data_f);
        //dd($data_f2);
        if($this->tipo == "2"){
            $data = [
                'imagen'            =>  $data_f,
                'no_expediente'     =>  $this->alumno->noexpediente,
                'alumno_id'         =>  $this->alumno->id,
                'filename'         =>   $nombre.$this->alumno->noexpediente.substr($this->file->getRealPath(),-4),
                'filesize'          =>  filesize($this->file->getRealPath()),
                'tipo'              =>  $this->tipo,
            ];
        }else{
            $data = [
                'imagen'            =>  $data_f,
                'no_expediente'     =>  $this->alumno->noexpediente,
                'alumno_id'         =>  $this->alumno->id,
                'filename'         =>   $nombre.$this->alumno->noexpediente.substr($this->file->getRealPath(),-4),
                'filesize'          =>  filesize($this->file->getRealPath()),
                'tipo'              =>  $this->tipo,
            ];
        }
        
        //dd($data);
        $imagen = ImagenesalumnoModel::where('alumno_id',$this->alumno->id)
            ->where('tipo',$this->tipo)->first();
        if($imagen)
        {
            //dd($imagen);
            $imagen->update($data);
        }
        else
        {
            
            ImagenesalumnoModel::create($data);

            //$imagen->save($data);
            //dd($imagen_creada);


            //dd("entre aqui");
        }

        BitacoraModel::create([
                'user_id'   =>  Auth()->user()->id,
                'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path'      =>  $_SERVER["REQUEST_URI"],
                'method'    =>  $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller'    =>  'ImagenAlumnoTipoComponent',
                //'component'     =>  'FormComponent',
                'function'  =>  'subir_archivo',
                'description'   =>  'Se actualizó la imagen tipo: '.$this->tipo.' del alumno_id: '.$this->alumno->id,
            ]);

        redirect()->route('adminalumnos.imagenalumno')->with('success','Imagen Actualizada correctamente');
    }

    public function borrar_foto($alumno_id, $documento){
        $buscar_imagen = ImagenesalumnoModel::where('alumno_id', $alumno_id)->where('tipo', $documento)->first();
        if ($buscar_imagen) {
            $buscar_imagen->delete();

        BitacoraModel::create([
            'user_id'   =>  Auth()->user()->id,
            'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
            'path'      =>  $_SERVER["REQUEST_URI"],
            'method'    =>  $_SERVER['REQUEST_METHOD'],
            //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
            'controller'    =>  'ImagenAlumnoTipoComponent',
            //'component'     =>  'FormComponent',
            'function'  =>  'Borrador de imagen',
            'description'   =>  'Se elimina la imagen tipo: '.$documento.' del alumno_id: '.$alumno_id,
        ]);

        $this->emit('foto_borrada');
        }

        

    }

    public function cambiar_doc($numero_asignado, $alumno_id){
        $this->documento = $numero_asignado;

        $archivo = ImagenesalumnoModel::where('tipo', $numero_asignado)
            ->where('alumno_id', $alumno_id)
            ->select('tipo', 'filename')
            ->first();

        if ($archivo) {
            // Obtiene la extensión del archivo del campo 'filename'
            $ext = strtolower(pathinfo($archivo->filename, PATHINFO_EXTENSION));

            // Mapea las extensiones a sus respectivos tipos MIME
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];

            // Asigna el tipo MIME a $this->tipo_archivo; si la extensión no se encuentra, asigna un tipo genérico
            $this->tipo_archivo = $mimeTypes[$ext] ?? 'application/octet-stream';

        }
    }


    public function render()
    {
        if($this->alumno_id)
        {
            if($this->alumno == null)
            {
                $this->alumno = AlumnoModel::find($this->alumno_id);
                $this->alumno_id_ant = $this->alumno_id;
            }
        }

        return view('livewire.adminalumnos.imagen.imagen-alumno-tipo-component');
    }
}
