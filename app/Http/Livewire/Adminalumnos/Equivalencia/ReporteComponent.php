<?php
// ANA MOLINA 22/07/2024
namespace App\Http\Livewire\Adminalumnos\Equivalencia;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\Adminalumnos\EquivalenciacalifModel;
use App\Models\Adminalumnos\EquivalenciaModel;

use App\Models\Adminalumnos\RevalidacioncalifModel;
use App\Models\Adminalumnos\RevalidacionModel;

use App\Models\Catalogos\PlantelesModel;
use App\Models\Adminalumnos\AlumnoModel;


class ReporteComponent extends Component
{
    public $equivalencia_id;
    public $tipo;

    public function render()
    {

        if ($this->tipo == 'E') {
            $equivalencia = EquivalenciaModel::find($this->equivalencia_id);
            $titulo = "EQUIVALENCIA DE ESTUDIOS";
            $calificaciones = EquivalenciacalifModel::select('calificacion', 'calif', 'nombre', 'periodo')
                ->join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_equivalenciacalif.asignatura_id')
                ->where('esc_equivalenciacalif.id', $equivalencia->id)
                ->orderby('periodo')->get();
            $numsem = '';
            if ($equivalencia->ciclo1 != 0)
                $numsem = $numsem . 'I';
            if ($equivalencia->ciclo2 != 0) {
                if ($numsem != '')
                    $numsem = $numsem . ", ";
                $numsem = $numsem . 'II';
            }
            if ($equivalencia->ciclo3 != 0) {
                if ($numsem != '')
                    $numsem = $numsem . ", ";

                $numsem = $numsem . 'III';
            }
            if ($equivalencia->ciclo4 != 0) {
                if ($numsem != '')
                    $numsem = $numsem . ", ";

                $numsem = $numsem . 'IV';
            }
            if ($equivalencia->ciclo5 != 0) {
                if ($numsem != '')
                    $numsem = $numsem . ", ";

                $numsem = $numsem . 'V';
            }
            $semestres = $numsem . " SEMESTRES, EQUIVALENCIA SEGÚN ACUERDO 286, PUBLICADO EN EL DIARIO OFICIAL DE LA FEDERACIÓN EL DÍA 30 DE OCTUBRE DE 2000, ART. 41.1 Y 41.4";
        } else
            if ($this->tipo == 'R') {
                $equivalencia = RevalidacionModel::find($this->equivalencia_id);
                $titulo = "REVALIDACIÓN DE ESTUDIOS";
                $calificaciones = RevalidacioncalifModel::select('nombre', 'periodo')
                    ->join('asi_asignatura', 'asi_asignatura.id', '=', 'esc_revalidacioncalif.asignatura_id')
                    ->where('esc_revalidacioncalif.id', $equivalencia->id)
                    ->orderby('periodo')->get();
                $semestres = "De conformidad con lo dispuesto en el Acuerdo 02/04/17 por el que se modifica el diverso 286, los estudios son equiparables con el " . $equivalencia->semestres . " SEMESTRES DE BACHILLERATO GENERAL del Sistema Educativo Nacional";
            }
        $plantel = PlantelesModel::find($equivalencia->plantel_id);
        $alumno = AlumnoModel::find($equivalencia->alumno_id);

        $data = DB::select("SELECT ciudad
        FROM cat_general
        where directorgeneral=1
        LIMIT 1");

        $usr = DB::select("SELECT name
        FROM users
        where id=" . $equivalencia->user_id);


        if ($equivalencia->user_id_aut) {
            $usra = DB::select("SELECT name
        FROM users
        where id=" . $equivalencia->user_id_aut);
            $autoriza = $usra[0]->name;
        } else {
            
            $autoriza = "SIN AUTORIZAR";
        }




        $result = [
            'folio' => $equivalencia->id,
            'titulo' => $titulo,
            'alumno' => $alumno->apellidos . ' ' . $alumno->nombre,
            'plantel' => $plantel->nombre,
            'fecha' => $equivalencia->created_at,
            'noexpediente' => $alumno->noexpediente,
            'procedencia' => $equivalencia->institucion,
            'semestres' => $semestres,
            'ciudad' => $data[0]->ciudad,
            'elaboro' => $plantel->director,
            'autorizo' => $autoriza,
            'fecha_aut' => $equivalencia->fecha_aut
        ];




        return view('livewire.adminalumnos.equivalencia.reporte-component', compact('result', 'calificaciones'));



    }


}
