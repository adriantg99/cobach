<?php
// ANA MOLINA 03/05/2024
namespace App\Http\Livewire\Certificados\Verificafolio;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Certificados\CertificadoModel;

use App\Models\Adminalumnos\ImagenesalumnoModel;

class TableComponent extends Component
{
    public $folio;
    public $curp;
    public $nombrealumno;
    public $fecha_certificado;
    public $img;
    public $plantel;
    public $digital;
    public $asignaturas;
    public $promedio;
    public $estatus;
    public $vigente;

    public $message = '';

    public function render()
    {
        $folio = $this->folio;
        $curp = $this->curp;
        self::buscar();

        return view('livewire.certificados.verificafolio.table-component', compact('curp', 'folio'));

    }
    public function buscar()
    {
        $this->message = '';
        if ($this->curp != "") {
            $cert = CertificadoModel::select('fecha_certificado', 'estatus', 'nombrealumno', 'fotocertificado', 'vigente', 'asignaturas', 'promedio', 'digital', 'cat_plantel.nombre', 'alu_alumno.noexpediente', 'alumno_id', 'esc_certificado.curp')
                ->join('alu_alumno', 'esc_certificado.alumno_id', '=', 'alu_alumno.id')
                ->join('cat_plantel', 'esc_certificado.plantel_id', '=', 'cat_plantel.id')
                ->where('esc_certificado.folio', $this->folio)->where('esc_certificado.curp', $this->curp)
                ->first();

                $alumno = AlumnoModel::where('curp', $this->curp)->first();

        } else {
            $cert = CertificadoModel::select('fecha_certificado', 'estatus', 'nombrealumno', 'fotocertificado', 'vigente', 'asignaturas', 'promedio', 'digital', 'cat_plantel.nombre', 'alu_alumno.noexpediente', 'alumno_id', 'esc_certificado.curp')
                ->join('alu_alumno', 'esc_certificado.alumno_id', '=', 'alu_alumno.id')
                ->join('cat_plantel', 'esc_certificado.plantel_id', '=', 'cat_plantel.id')
                ->where('esc_certificado.folio', $this->folio)
                ->first();
                $alumno = AlumnoModel::where('id', $cert->alumno_id)->first();
                $this->curp = "CURP NO ENCONTRADO";
        }


        
        $view = DB::table('view_alumno_certifica')->where('alumno_id', $alumno->id)->first();
        $calificacionescer = DB::select('call pa_certificado(?)', array($alumno->id));
        //dd($alumno, $cert);
        $suma = 0;
        $materias = 0;
        $materias_con_rev = 0;

        foreach ($calificacionescer as $cal) {
            if (isset($cal->periodo1)) {
                if ($cal->calificacion1 != "REV") {
                    if ($cal->calificacion1 >= 60 || $cal->calificacion1 != "NA") {
                        // $materias = $materias + 1;
                    }
                    if ($cal->calificacion1 == "AC" || $cal->calificacion1 == "NA") {

                    } else {
                        $suma = $suma + $cal->calificacion1;
                    }

                } else {
                    $materias_con_rev = $materias_con_rev + 1;
                }

            }
            if (isset($cal->periodo2)) {
                if ($cal->calificacion2 != "REV") {
                    if ($cal->calificacion2 >= 60 || $cal->calificacion2 != "NA") {
                        //$materias = $materias + 1;
                    }
                    if ($cal->calificacion2 == "AC" || $cal->calificacion2 == "NA") {

                    } else {
                        $suma = $suma + $cal->calificacion2;
                    }
                } else {
                    $materias_con_rev = $materias_con_rev + 1;
                }

            }
            $materias = $cal->count_materia1 + $cal->count_materia2;
        }

        /*
        foreach ($calificacionescer as $cal) {
            if (isset($cal->periodo1)) {
                if($cal->calificacion1 != "AC" && $cal->calificacion1 != "NA" && $cal->calificacion1 != "REV"){
                    //$materias = $materias + 1;
                    $suma = $suma + $cal->calificacion1;
                }
                else{
                    //$materias = $materias + 1;
                }
                
            }
            if (isset($cal->periodo2)) {
                if($cal->calificacion2 != "AC" && $cal->calificacion2 != "NA" && $cal->calificacion2 != "REV"){
                    //$materias = $materias + 1;
                    $suma = $suma + $cal->calificacion2;
                }
                else{
                    //$materias = $materias + 1;
                }
            }

            $materias = $cal->count_materia1 + $cal->count_materia2;
        }*/
        $promedio = $suma / ($materias - $materias_con_rev);
        if (isset($cert)) {
            $timed = strtotime($cert->fecha_certificado);
            $newfc = date('d-m-Y', $timed);
            $timed = strtotime($cert->digital);
            $newd = date('d-m-Y', $timed);
            $this->nombrealumno = $cert->nombrealumno;
            $this->fecha_certificado = $newfc;
            $this->img = $cert->img;
            $this->plantel = $cert->nombre;
            $this->digital = $newd;
            $this->asignaturas = $materias;
            $this->promedio = number_format($promedio);
            if ($cert->estatus == "T")

                $this->estatus = "TOTAL";
            else
                $this->estatus = "PARCIAL";
            if ($cert->vigente)
                $this->vigente = "VIGENTE";
            else
                $this->vigente = "CANCELADO";
            $imagen_find = ImagenesalumnoModel::where('alumno_id', $cert->alumno_id)->where('tipo', 2)->get();
            //si no tiene foto, no genera certificado
            if ($imagen_find->count() > 0) {
                $this->img = $imagen_find[0]['imagen'];
            }
        } else {
            $this->nombrealumno = '';
            $this->fecha_certificado = '';
            $this->img = null;
            $this->plantel = '';
            $this->digital = '';
            $this->asignaturas = '';
            $this->promedio = '';
            $this->estatus = '';
            $this->vigente = '';
            $this->message = 'Certificado NO existe.';

            $this->dispatchBrowserEvent('noencuentra');
        }

    }


}

