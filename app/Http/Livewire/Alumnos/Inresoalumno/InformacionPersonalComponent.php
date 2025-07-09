<?php

namespace App\Http\Livewire\Alumnos\Inresoalumno;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class InformacionPersonalComponent extends Component
{

    use WithFileUploads;
    public $file;

    public $alumno, $estados;
    public $seccion_actual;

    public $file_acta, $file_certificado, $file_foto, $file_curp;
    protected $listeners = ['guardar_informacion', 'saveFile', 'loadLocalidades'];

    // Campos del formulario
    public $telefono_contacto;
    public $correo_electronico_personal;
    public $entidad_id;
    public $municipio_id;
    public $municipios;
    public $localidad_id;
    public $nombre_tutor;
    public $apellido_paterno_tutor;
    public $apellido_materno_tutor;
    public $correo_electronico_tutor;
    public $telefono_tutor;
    public $colonia_tutor;
    public $domicilio_tutor;
    public $nombre_familiar;
    public $apellido_paterno_familiar;
    public $apellido_materno_familiar;
    public $correo_electronico_familiar;
    public $telefono_familiar;
    public $alergias;
    public $meds_permit, $medicamentos_perimitidos;
    public $problemas_salud;
    public $id_servicio_medico;
    public $otros_medico, $localidades = [];
    public $filiacion, $alergias_describe;
    public $previa;


    public function render()
    {
        /*$this->file_acta $this->file_certificado */ /* $this->file_curp;*/
        //$this->message = "Estoy cargando dinámicamente en el segundo " . now()->format('s');
if (!empty($this->alumno->id_estadodomicilio)) {
            // Cargar todos los estados
            $this->estados = collect(DB::table('cat_localidades')
                ->select(DB::raw('count(id) as locs, cve_ent, nom_ent'))
                ->groupBy('cve_ent', 'nom_ent')
                ->orderBy('cve_ent')
                ->get());
        
            // Cargar los municipios del estado precargado
            $this->municipios = collect(DB::table('cat_localidades')
                ->select(DB::raw('count(id) as muns, cve_ent, cve_mun, nom_mun'))
                ->groupBy('cve_ent', 'cve_mun', 'nom_mun')
                ->where('cve_ent', $this->alumno->id_estadodomicilio)
                ->orderBy('cve_mun')
                ->get());
        
            // Cargar las localidades del municipio y estado precargados
            if (!empty($this->alumno->id_municipiodomicilio)) {
                $this->localidades = collect(DB::table('cat_localidades')
                    ->where('cve_ent', $this->alumno->id_estadodomicilio)
                    ->where('cve_mun', $this->alumno->id_municipiodomicilio)
                    ->orderBy('nom_loc')
                    ->get());
            } else {
                $this->localidades = collect(); // Vacío si no hay municipio
            }
        } else {
            // Si no hay estado cargado, usar estado por defecto (ejemplo: Sonora cve_ent = 26)
            $this->estados = collect(DB::table('cat_localidades')
                ->select(DB::raw('count(id) as locs, cve_ent, nom_ent'))
                ->groupBy('cve_ent', 'nom_ent')
                ->orderBy('cve_ent')
                ->get());
        
            $this->municipios = collect(DB::table('cat_localidades')
                ->select(DB::raw('count(id) as muns, cve_ent, cve_mun, nom_mun'))
                ->groupBy('cve_ent', 'cve_mun', 'nom_mun')
                ->where('cve_ent', '26') // por defecto
                ->orderBy('cve_mun')
                ->get());
        
            $this->localidades = collect(); // Vacío hasta que se seleccione un municipio
        }
    
        return view('livewire.alumnos.inresoalumno.informacion-personal-component');
    }

    public function cambio_ventana($ventana_anterior){
        
        $this->dispatchBrowserEvent('ejecutar-js', ['ventana_anterior' => $this->ventana_anterior]);
        $this->ventana_anterior = $ventana_anterior;
    }


    public function loadLocalidades($entidad_id, $municipioId)
    {
        $entidad_id = intval($entidad_id);
        $municipioId = intval($municipioId);
        $this->localidades = DB::table('cat_localidades')->select(DB::raw('count(id) as muns, cve_ent, cve_mun, cve_loc, nom_loc'))
            ->groupBy('cve_ent', 'cve_mun', 'cve_loc', 'nom_loc')
            ->where('cve_ent', intval($entidad_id))->where('cve_mun', intval($municipioId))
            ->orderBy('cve_loc')
            ->get();
        //dd($this->localidades);
    }

    public function mount($alumno, $estados)
    {
        $this->alumno = $alumno;

        $this->nombre_tutor = $alumno->nombre_tutor;
        $this->apellido_paterno_tutor = $alumno->apellido_paterno_tutor;
        $this->apellido_materno_tutor = $alumno->apellido_materno_tutor;
        $this->correo_electronico_tutor = $alumno->correo_electronico_tutor;
        $this->telefono_tutor = $alumno->telefono_tutor;
        $this->colonia_tutor = $alumno->colonia_tutor;
        $this->domicilio_tutor = $alumno->domicilio_tutor;
        $this->nombre_familiar = $alumno->nombre_familiar;
        $this->apellido_paterno_familiar = $alumno->apellido_paterno_familiar;
        $this->apellido_materno_familiar = $alumno->apellido_materno_familiar;
        $this->correo_electronico_familiar = $alumno->correo_electronico_familiar;
        $this->telefono_familiar = $alumno->telefono_familiar;
        //$this->alergias = $alumno->alergias_describe;
        $this->meds_permit = $alumno->meds_permit;
        $this->problemas_salud = $alumno->problemas_salud;
        $this->id_servicio_medico = $alumno->id_servicio_medico;
        $this->otros_medico = $alumno->otros_medico;
        $this->filiacion = $alumno->filiacion;
        $this->alergias_describe = $alumno->alergias_describe;

        //dd($this->alumno);

        $this->estados = collect($estados);




        $this->alumno = $alumno;
        //$this->estados = Estado::all();
        $this->telefono_contacto = $alumno->telefono;
        $this->correo_electronico_personal = $alumno->email;
        $this->ventana_anterior = 'datos_contacto';
        // Asigna otros campos similares aquí...
        
    }

    public function guardar_informacion($meds_permit, $discapacidad_describe, $id_servicio_medico, $servicio_medico_otro, $servicio_medico_afiliacion)
    {

        $buscar_alumno = AlumnoModel::find($this->alumno->id);
        //dd($meds_permit);
        $buscar_alumno->meds_permit = $meds_permit;
        $buscar_alumno->discapacidad_describe = $discapacidad_describe;
        $buscar_alumno->id_servicio_medico = $id_servicio_medico;
        $buscar_alumno->servicio_medico_otro = $servicio_medico_otro;
        $buscar_alumno->servicio_medico_afiliacion = $servicio_medico_afiliacion;
        $buscar_alumno->save();
        //dd($buscar_alumno);
    }

}
