<?php

namespace App\Http\Livewire\Grupos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;

class Create extends Component
{
    public $cantidad_grupos;
    public $cve_turno;
    public $Plantel;
    public $cve_plantel;
    public $semestre;
    public $cve_ciclo;
    public $cve_periodo;
    public $capacidad_grupo;
    public $mensaje;
    public $grupos_base = false;

    public function guardar()
    {
        $rules = [
            'cve_turno' => 'required|max:100',
            'cve_plantel' => 'required|numeric',
            'cve_ciclo' => 'required|numeric',
            'cve_periodo' => 'required|max:10',
            'capacidad_grupo' => 'nullable|numeric',
            'cantidad_grupos' => 'required|max:10'
        ];
        $this->validate($rules);
        $num = intval(preg_replace('/[^0-9]/', '', $this->cantidad_grupos));
        //$this->mensaje = 'Estoy aqui';
        if (Auth()->user()->hasPermissionTo('grupos-crear')) {
            $anterior_creado = GruposModel::select('periodo')
                ->where('periodo', $this->cve_periodo)
                ->where('turno_id', $this->cve_turno)
                ->where('ciclo_esc_id', $this->cve_ciclo)
                ->where('plantel_id', $this->cve_plantel)
                ->orderBy('id','desc')
                ->count();
            for ($consecutivo = 1; $consecutivo <= $num; $consecutivo++) {
                $numeroEntero = intval($anterior_creado);
                //dd($numeroEntero);
                if($numeroEntero+$consecutivo >=10){
                    $nombre_para_buscar = $this->cve_periodo . $numeroEntero+$consecutivo;
                    //sdd($this->cve_periodo.$numeroEntero+$consecutivo);
                    $data = [
                        'turno_id' => $this->cve_turno,
                        'plantel_id' => $this->cve_plantel,
                        'ciclo_esc_id' => $this->cve_ciclo,
                        'periodo' => $this->cve_periodo,
                        'capacidad' => $this->capacidad_grupo,
                        'nombre' => $this->cve_periodo . $numeroEntero+$consecutivo,
                        'descripcion' => $this->cve_periodo . $numeroEntero+$consecutivo,
                        'gpo_base' => $this->grupos_base,
                    ];
                }else{
                    $nombre_para_buscar = $this->cve_periodo . '0' . $numeroEntero+$consecutivo;
                    $data = [
                        'turno_id' => $this->cve_turno,
                        'plantel_id' => $this->cve_plantel,
                        'ciclo_esc_id' => $this->cve_ciclo,
                        'periodo' => $this->cve_periodo,
                        'capacidad' => $this->capacidad_grupo,
                        'nombre' => $this->cve_periodo . '0' . $numeroEntero+$consecutivo,
                        'descripcion' => $this->cve_periodo . '0' . $numeroEntero+$consecutivo,
                        'gpo_base' => $this->grupos_base,
                    ];
                }

                //En caso de que exista un grupo con el mismo nombre No lo crea
                
                $existe = GruposModel::where('plantel_id', $this->cve_plantel)
                    ->where('turno_id', $this->cve_turno)
                    ->where('ciclo_esc_id', $this->cve_ciclo)
                    ->where('nombre', $nombre_para_buscar)
                    ->first();
                if($existe==null)
                {
                    GruposModel::create($data);
                }
            }

            redirect()->route('Grupos.crear.index')->with('success','Grupos creados correctamente');
        }
        else{
            $this->mensaje = 1;
        }

    }
    public function mount()
    {
        $this->Plantel = obtenerPlanteles();
        $this->Ciclos = CicloEscModel::orderBy('id', 'desc')->whereYear('per_inicio', '>=', now()->year)->get();

    }

    public function render()
    {
        return view('livewire.grupos.create');
    }
}
