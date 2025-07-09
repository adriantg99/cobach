<?php

// ANA MOLINA 30/08/2024
namespace App\Exports\Estadisticas;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Cursos\CursosModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\Administracion\PerfilModel;

use Carbon\Carbon;
class TableroExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public  $plantel,  $periodo,   $grupo,   $ciclo, $curso, $docente, $turno,$vars , $chk1,$chk2,$chk3,$chkr,$chkf;

    public function  __construct( $ciclo,$plantel,  $periodo,   $grupo,$turno, $curso,$docente, $vars ,$chk1,$chk2,$chk3,$chkr,$chkf)
    {
        //decodificar
        $this->plantel =base64_decode( $plantel);
        $this->periodo=base64_decode($periodo);

        $this->grupo = base64_decode($grupo);
        $this->ciclo=base64_decode($ciclo);
        $this->turno=base64_decode($turno);
        $this->curso=utf8_encode(base64_decode($curso));

        $this->docente=base64_decode($docente);
        $this->vars =base64_decode( $vars);
        $this->chk1 =base64_decode( $chk1);
        $this->chk2 =base64_decode( $chk2);
        $this->chk3 =base64_decode( $chk3);
        $this->chkr =base64_decode( $chkr);
        $this->chkf =base64_decode( $chkf);

    }

    public function view(): View
    {

         $fecha = Carbon::now();
         ini_set('max_execution_time', 600); // 5 minutes

        $dashb= DB::select('call pa_dashboard(?,?,?,?,?,?,?,?)',array($this->ciclo,$this->plantel,$this->grupo,$this->curso, $this->docente,$this->turno,$this->periodo,$this->vars));
       $vars=$this->vars;

        $ciclodes = CicloEscModel::where('id',$this->ciclo)->first();
        $ciclonom=$ciclodes->nombre;

        if($this->plantel!='|')
        {
            $planteldes = PlantelesModel::where('id',$this->plantel)->first();
            $plantelnom=$planteldes->nombre;
        }
        else
            $plantelnom='';
        if($this->periodo!='0')
        {
            $periodonom=$this->periodo;
        }
        else
            $periodonom='';
        if($this->grupo!='0')
        {
            $grupodes = GruposModel::where('id',$this->grupo)->first();
            $gruponom=$grupodes->nombre;
        }
        else
            $gruponom='';

        if($this->curso!='0')
        {
            //$cursodes = CursosModel::where('id',$this->curso)->first();
            $cursodes = CursosModel::where('nombre',$this->curso)->first();
            $cursonom=$cursodes->nombre;
        }
        else
            $cursonom='';
        if($this->docente!='0')
            {
                $docentedes = PerfilModel::where('id',$this->docente)->first();
                if($docentedes)
                    $docentenom=$docentedes->apellido1.' '.$docentedes->apellido2.' '.$docentedes->nombre;
                else
                    $docentenom='';
            }
            else
                $docentenom='';

        if($this->turno!='0')
            {
                if($this->turno==1)
                    $turnonom='MATUTINO';
                else
                if($this->turno==2)
                    $turnonom='VESPERTINO';
            }
            else
                $turnonom='';

        $chk1=$this->chk1;
        $chk2=$this->chk2;
        $chk3=$this->chk3;
        $chkr=$this->chkr;
        $chkf=$this->chkf;
         return view('exports.estadisticas.tablero_excel', compact('dashb','fecha','vars','ciclonom','plantelnom','periodonom','gruponom','cursonom','docentenom','turnonom','chk1','chk2','chk3','chkr','chkf'));
    }
}
