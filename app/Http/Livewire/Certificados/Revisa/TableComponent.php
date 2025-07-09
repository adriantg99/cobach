<?php
// ANA MOLINA 06/03/2024
namespace App\Http\Livewire\Certificados\Revisa;


use App\Models\Administracion\BitacoraModel;
use App\Models\Certificados\CertificadoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Adminalumnos\AlumnoModel;


class TableComponent extends Component
{
    public $id_ciclo;
    public $id_plantel;

    public $id_grupo;

    public $alumnos_sel = '';


    public $chkall = true;


    public function render()
    {

        $ciclos = CicloEscModel::select('id', 'nombre', 'per_inicio')->orderBy('per_inicio', 'desc')
            ->get();
        $planteles = PlantelesModel::select('id', 'nombre')->orderBy('id')
            ->get();
        $grupos = GruposModel::select('id', 'nombre', 'turno_id', 'descripcion')->where('plantel_id', $this->id_plantel)->where(
            'ciclo_esc_id',
            $this->id_ciclo
        )->orderBy('nombre')
            ->get();
        $getalumnos = null;
        if (!empty($this->id_grupo)) {
            ini_set('max_execution_time', 600); // 5 minutes
            $getalumnos = DB::select('call pa_alumnos_certrevisa_lst(?)', array($this->id_grupo));
            //dd( $this->id_grupo);
        }

        return view('livewire.certificados.revisa.table-component', compact('ciclos', 'planteles', 'grupos', 'getalumnos'));
    }

    public function actualizarFechaCertificado($alumnoId, $nuevaFecha)
    {
        $buscar_certificado = CertificadoModel::where('alumno_id', $alumnoId)
            ->where('vigente', 1)->orderBy('created_at', 'desc')->first();

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'Certificados Fecha',
                //'component'     =>  'FormComponent',
                'function' => 'Actualizar Fecha',
                'description' => 'Se actualizo la fecha de emisión del certificado del alumno_id: '. 
                $buscar_certificado->alumno_id.'De la fecha: ' .$buscar_certificado->fecha_certificado. '. Con la nueva: '
                .$nuevaFecha,
            ]);
        $buscar_certificado->fecha_certificado = $nuevaFecha;


        $buscar_certificado->save();

        

        $this->emit('fechaCertificadoActualizada', $buscar_certificado->nombrealumno);
    }

    public function duplicado($alumno_id){
        $buscar_certificado = CertificadoModel::where('alumno_id', $alumno_id)->where('vigente', 1)->orderBy('created_at', 'desc')->first();
        if($buscar_certificado->original == 1){
            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'Certificados Duplicado',
                //'component'     =>  'FormComponent',
                'function' => 'Certificado Duplicado',
                'description' => 'Cambio a duplicado del certificado: '. $buscar_certificado->id
            ]);
            $buscar_certificado->original = 2;
            $buscar_certificado->save();

            $this->emit('cambio_duplicidad', $buscar_certificado->nombrealumno, $buscar_certificado->original);

        }
        else{

            BitacoraModel::create([
                'user_id' => Auth()->user()->id,
                'ip' => (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'path' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER['REQUEST_METHOD'],
                //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                'controller' => 'Certificados Duplicado',
                //'component'     =>  'FormComponent',
                'function' => 'Certificado Duplicado',
                'description' => 'Cambio a original el certificado: '. $buscar_certificado->id
            ]);
            $buscar_certificado->original = 1;
            $buscar_certificado->save();

            $this->emit('cambio_duplicidad', $buscar_certificado->nombrealumno, $buscar_certificado->original);

        }
     
    }

}

