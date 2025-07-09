<?php

namespace App\Http\Livewire\Docentes;

use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\DocenteModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Docentes\AperturaModel;
use App\Models\Grupos\GruposModel;
use Livewire\Component;

class AperturaComponent extends Component
{
    public $plantel, $grupo, $docente, $plantel_seleccionado,
    $parcial_seleccionado, $fecha_cierre, $docente_seleccionar,
    $grupo_seleccionar, $ciclo_abierto;
    public function render()
    {

        if ($this->plantel_seleccionado != null) {
            $this->docente = DocenteModel::join('emp_perfil_plantele', 'emp_perfil.id', '=', 'emp_perfil_plantele.perfil_id')
                ->where('emp_perfil_plantele.plantel_id', $this->plantel_seleccionado)
                ->select('emp_perfil.*')
                ->orderBy('emp_perfil.apellido1')
                ->get();
            //dd($this->docente);

            if ($this->docente_seleccionar != null) {
                $this->grupo = GruposModel::
                    join('esc_curso', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_curso.docente_id', $this->docente_seleccionar)
                    ->where('ciclo_esc_id', 250)
                    ->select('esc_grupo.*', 'esc_curso.nombre as materia')
                    ->orderBy('esc_grupo.periodo')
                    ->get();
            }
        }
        return view('livewire.docentes.apertura-component');
    }

    public function mount()
    {
        $this->ciclo_abierto = CicloEscModel::where('activo', '1')->first();
        $this->plantel = PlantelesModel::get();
    }


    public function guardar_apertura()
    {
        $valida_ciclo = CicloEscModel::where('activo', '1')->first();
        //dd($valida_ciclo);

        if($this->plantel_seleccionado !=0){
            if ($this->docente_seleccionar == 0) {
                if ($this->parcial_seleccionado != null) {
                    if ($this->fecha_cierre == null) {
                        $this->emit('falta_fecha');
                    }
    
                    foreach ($this->docente as $docente_plantel) {
                        $buscar_grupos = GruposModel::join('esc_curso', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
                            ->where('ciclo_esc_id', $valida_ciclo->id)
                            ->where('plantel_id', $this->plantel_seleccionado)
                            ->where('esc_curso.docente_id', $docente_plantel->id)
                            ->select('esc_grupo.*')
                            ->get();
    
                        //dd($buscar_grupos->toSql(), $buscar_grupos->getBindings());
    
    
                        //dd($buscar_grupos);
                        foreach ($buscar_grupos as $auxiliar_grupos) {
                            $valida_apertura = AperturaModel::where('grupo_id', $auxiliar_grupos->id)
                                ->where('parcial', $this->parcial_seleccionado)
                                ->where('ciclos_esc_id', $valida_ciclo->id)
                                ->where('emp_perfil_id', $this->docente_seleccionar)
                                ->first();
    
                            if ($valida_apertura) {
                                $valida_apertura->nuevo_cierre = $this->fecha_cierre;
                                $valida_apertura->save();
                            } else {
                                AperturaModel::create([
                                    'emp_perfil_id' => $docente_plantel->id,
                                    'grupo_id' => $auxiliar_grupos->id,
                                    'parcial' => $this->parcial_seleccionado,
                                    'ciclos_esc_id' => $valida_ciclo->id,
                                    'activado' => "1",
                                    'nuevo_cierre' => $this->fecha_cierre,
                                ]);
                            }
                        }
                    }
    
                    $plantel = PlantelesModel::find($this->plantel_seleccionado);
                    return redirect()->route('apertura.index')->with('success', 'Captura abierta para el plantel: ' . $plantel->nombre);
                } else {
                    $this->emit('falta_parcial');
                }
    
            } elseif ($this->parcial_seleccionado != null) {
                if ($this->fecha_cierre != null) {
                    if ($this->grupo_seleccionar != null) {
                        $valida_apertura = AperturaModel::where('grupo_id', $this->grupo_seleccionar)
                            ->where('parcial', $this->parcial_seleccionado)
                            ->where('ciclos_esc_id', $valida_ciclo->id)
                            ->where('emp_perfil_id', $this->docente_seleccionar)
                            ->first();
    
                        if ($valida_apertura) {
                            
                            $valida_apertura->nuevo_cierre = $this->fecha_cierre;
                            $valida_apertura->save();
                            $docente = DocenteModel::find($this->docente_seleccionar);
                            $encontrar_grupo = GruposModel::find($this->grupo_seleccionar);
                            return redirect()->route('apertura.index')->with('success', 'Captura abierta para el Docente: ' . $docente->apellido1 . ' ' . $docente->apellido2 . ' ' . $docente->nombre .
                            ' Del grupo: ' . $encontrar_grupo->nombre. ' Con Fecha de cierre: '. $this->fecha_cierre);
                        } else {
                            AperturaModel::create([
                                'emp_perfil_id' => $this->docente_seleccionar,
                                'grupo_id' => $this->grupo_seleccionar,
                                'parcial' => $this->parcial_seleccionado,
                                'ciclos_esc_id' => $valida_ciclo->id,
                                'activado' => "1",
                                'nuevo_cierre' => $this->fecha_cierre,
                            ]);
                            $docente = DocenteModel::find($this->docente_seleccionar);
                            $encontrar_grupo = GruposModel::find($this->grupo_seleccionar);
                            return redirect()->route('apertura.index')->with('success', 'Captura abierta para el Docente: ' . $docente->apellido1 . ' ' . $docente->apellido2 . ' ' . $docente->nombre .
                                ' Del grupo: ' . $encontrar_grupo->nombre);
                        }
                    } elseif ($this->grupo_seleccionar == 0) {
                        $buscar_grupos = GruposModel::join('esc_curso', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
                            ->where('ciclo_esc_id', $valida_ciclo->id)
                            ->where('plantel_id', $this->plantel_seleccionado)
                            ->where('esc_curso.docente_id', $this->docente_seleccionar)
                            ->select('esc_grupo.*')
                            ->get();
                        if(count($buscar_grupos)== 0){
                            $this->emit('sin_grupos');
                        }
                        else{
                            foreach ($buscar_grupos as $auxiliar_grupos) {
                                $valida_apertura = AperturaModel::where('grupo_id', $auxiliar_grupos->id)
                                    ->where('parcial', $this->parcial_seleccionado)
                                    ->where('ciclos_esc_id', $valida_ciclo->id)
                                    ->where('emp_perfil_id', $this->docente_seleccionar)
                                    ->first();
        
                                if ($valida_apertura) {
                                    $valida_apertura->nuevo_cierre = $this->fecha_cierre;
                                    $valida_apertura->save();
                                } else {
                                    AperturaModel::create([
                                        'emp_perfil_id' => $this->docente_seleccionar,
                                        'grupo_id' => $auxiliar_grupos->id,
                                        'parcial' => $this->parcial_seleccionado,
                                        'ciclos_esc_id' => $valida_ciclo->id,
                                        'activado' => "1",
                                        'nuevo_cierre' => $this->fecha_cierre,
                                    ]);
                                }
                            }
                            $docente = DocenteModel::find($this->docente_seleccionar);
                            return redirect()->route('apertura.index')->with('success', 'Captura abierta para el Docente: ' . $docente->apellido1 . ' ' . $docente->apellido2 . ' ' . $docente->nombre);
                        }                
                    }
    
                } else {
                    $this->emit('falta_fecha');
    
                }
            } else {
                $this->emit('falta_parcial');
            }
        }
        else{
            $this->emit('falta_plantel');

        }

        
    }
}
