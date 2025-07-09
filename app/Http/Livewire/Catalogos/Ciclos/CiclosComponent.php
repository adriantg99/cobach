<?php

namespace App\Http\Livewire\Catalogos\Ciclos;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\CiclosConfigModel;
use Illuminate\Http\Request;


use Livewire\Component;

class CiclosComponent extends Component
{

    public $ciclo;

    public $datos_ciclo = [
        'id',
        'nombre',
        'abreviatura',
        'per_inicio',
        'per_final',
        'activo',
        'p1',
        'p2',
        'p3',
        'fin_p1',
        'fin_p2',
        'fin_p3',
        'inicio_inscripcion',
        'fin_inscripcion',
        'inicio_repeticion',
        'fin_repeticion',
        'inicio_reinscripcion',
        'fin_reinscripcion',
        'tipo',
    ];
    public function render()
    {
        return view('livewire.catalogos.ciclos.ciclos-component');
    }

    public function mount(Request $request)
    {
        $ciclo = $request->route('ciclo_esc_id');

        $this->ciclo = CicloEscModel::find($ciclo);

        if ($this->ciclo) {
            $configuraciones_ciclo = CiclosConfigModel::where('ciclo_esc_id', $this->ciclo->id)->first();

            if ($configuraciones_ciclo) {

                $this->datos_ciclo = [
                    'id' => $this->ciclo->id,
                    'nombre' => $this->ciclo->nombre,
                    'abreviatura' => $this->ciclo->abreviatura,
                    'per_inicio' => $this->ciclo->per_inicio,
                    'per_final' => $this->ciclo->per_final,
                    'activo' => $this->ciclo->activo,
                    'tipo' => $this->ciclo->tipo ?? '0',
                    'p1' => $configuraciones_ciclo->p1,
                    'p2' => $configuraciones_ciclo->p2,
                    'p3' => $configuraciones_ciclo->p3,
                    'fin_p1' => $configuraciones_ciclo->fin_p1,
                    'fin_p2' => $configuraciones_ciclo->fin_p2,
                    'fin_p3' => $configuraciones_ciclo->fin_p3,
                    'inicio_inscripcion' => $configuraciones_ciclo->inicio_inscripcion,
                    'fin_inscripcion' => $configuraciones_ciclo->fin_inscripcion,
                    'inicio_repeticion' => $configuraciones_ciclo->inicio_repeticion,
                    'fin_repeticion' => $configuraciones_ciclo->fin_repeticion,
                    'inicio_reinscripcion' => $configuraciones_ciclo->inicio_reinscripcion,
                    'fin_reinscripcion' => $configuraciones_ciclo->fin_reinscripcion,
                ];
            } else {
                $this->datos_ciclo = [
                    'id' => $this->ciclo->id,
                    'nombre' => $this->ciclo->nombre,
                    'abreviatura' => $this->ciclo->abreviatura,
                    'tipo' => $this->ciclo->tipo ?? '0',

                    'per_inicio' => $this->ciclo->per_inicio,
                    'per_final' => $this->ciclo->per_final,
                    'activo' => $this->ciclo->activo,
                ];

                $this->datos_ciclo["p1"] = null;
                $this->datos_ciclo["p2"] = null;
                $this->datos_ciclo["p3"] = null;
                $this->datos_ciclo["fin_p1"] = null;
                $this->datos_ciclo["fin_p2"] = null;
                $this->datos_ciclo["fin_p3"] = null;
                $this->datos_ciclo["inicio_inscripcion"] = null;
                $this->datos_ciclo["fin_inscripcion"] = null;
                $this->datos_ciclo["inicio_repeticion"] = null;
                $this->datos_ciclo["fin_repeticion"] = null;
                $this->datos_ciclo["inicio_reinscripcion"] = null;
                $this->datos_ciclo["fin_reinscripcion"] = null;
            }

        } else {

            $this->datos_ciclo = [
                'id' => '',
                'nombre' => '',
                'abreviatura' => '',
                'per_inicio' => '',
                'per_final' => '',
                'activo' => '',
                'p1' => '',
                'p2' => '',
                'p3' => '',
                'fin_p1' => '',
                'fin_p2' => '',
                'fin_p3' => '',
                'inicio_inscripcion' => '',
                'fin_inscripcion' => '',
                'inicio_repeticion' => '',
                'fin_repeticion' => '',
                'inicio_reinscripcion' => '',
                'fin_reinscripcion' => '',
                'tipo' => '0',
            ];
        }
    }

    public function guardar()
    {
        // Normalizar el valor de 'activo'
        $this->datos_ciclo["activo"] = (int) ($this->datos_ciclo["activo"] ?? 0);
        if ($this->datos_ciclo["activo"] === 1) {
            $cambiar_ciclos = CicloEscModel::where('activo', 1)->get();
            foreach ($cambiar_ciclos as $ciclo) {
                $ciclo->activo = 0;
                $ciclo->save();
            }
        }
        // Validación básica para los datos del ciclo
        $rules = [
            'datos_ciclo.nombre' => 'required|max:100',
            'datos_ciclo.abreviatura' => 'required|max:100',
            'datos_ciclo.per_inicio' => 'required|date',
            'datos_ciclo.per_final' => 'required|date',
            'datos_ciclo.tipo' => 'required|max:2',
        ];

        // Validar solo si activo = 1
        if ($this->datos_ciclo["activo"] === 1) {
            $rules = array_merge($rules, [
                'datos_ciclo.p1' => 'required|date',
                'datos_ciclo.p2' => 'required|date',
                'datos_ciclo.p3' => 'required|date',
                'datos_ciclo.fin_p1' => 'required|date',
                'datos_ciclo.fin_p2' => 'required|date',
                'datos_ciclo.fin_p3' => 'required|date',
                'datos_ciclo.inicio_repeticion' => 'required|date',
                'datos_ciclo.fin_repeticion' => 'required|date',
            ]);
        }

        // Validar los datos del ciclo
        $this->validate($rules);

        if ($this->ciclo) {
            // Actualizar el ciclo existente
            $this->ciclo->update([
                'nombre' => $this->datos_ciclo["nombre"],
                'abreviatura' => $this->datos_ciclo["abreviatura"],
                'per_inicio' => $this->datos_ciclo["per_inicio"],
                'per_final' => $this->datos_ciclo["per_final"],
                'activo' => $this->datos_ciclo["activo"],
            ]);

            // Crear o actualizar configuración solo si activo = 1
            if ($this->datos_ciclo["activo"] === 1) {
                $this->crear_config($this->ciclo->id);
            }
        } else {
            // Crear un nuevo ciclo
            $nuevo_ciclo = CicloEscModel::create([
                'nombre' => $this->datos_ciclo["nombre"],
                'abreviatura' => $this->datos_ciclo["abreviatura"],
                'per_inicio' => $this->datos_ciclo["per_inicio"],
                'per_final' => $this->datos_ciclo["per_final"],
                'activo' => $this->datos_ciclo["activo"],
            ]);

            // Crear configuración solo si activo = 1
            if ($this->datos_ciclo["activo"] === 1) {
                $this->crear_config($nuevo_ciclo->id);
            }
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('catalogos.ciclosesc.index')->with('success', 'Ciclo guardado con éxito.');
    }

    private function crear_config($ciclo_id)
    {
        // Obtener la configuración existente
        $config = CiclosConfigModel::where('ciclo_esc_id', $ciclo_id)->first();

        if ($config) {
            // Actualizar la configuración existente
            $config->update([
                'p1' => $this->datos_ciclo["p1"],
                'p2' => $this->datos_ciclo["p2"],
                'p3' => $this->datos_ciclo["p3"],
                'fin_p1' => $this->datos_ciclo["fin_p1"],
                'fin_p2' => $this->datos_ciclo["fin_p2"],
                'fin_p3' => $this->datos_ciclo["fin_p3"],
                'inicio_inscripcion' => $this->datos_ciclo["inicio_inscripcion"],
                'fin_inscripcion' => $this->datos_ciclo["fin_inscripcion"],
                'inicio_repeticion' => $this->datos_ciclo["inicio_repeticion"],
                'fin_repeticion' => $this->datos_ciclo["fin_repeticion"],
                'inicio_reinscripcion' => $this->datos_ciclo["inicio_reinscripcion"],
                'fin_reinscripcion' => $this->datos_ciclo["fin_reinscripcion"],
            ]);
        } else {
            // Crear nueva configuración

            if ($this->ciclo["tipo"] === "N") {

            }

            switch ($this->ciclo["tipo"]) {
                case 'N':
                    CiclosConfigModel::create([
                        'ciclo_esc_id' => $ciclo_id,
                        'p1' => $this->datos_ciclo["p1"],
                        'p2' => $this->datos_ciclo["p2"],
                        'p3' => $this->datos_ciclo["p3"],
                        'fin_p1' => $this->datos_ciclo["fin_p1"],
                        'fin_p2' => $this->datos_ciclo["fin_p2"],
                        'fin_p3' => $this->datos_ciclo["fin_p3"],
                        'inicio_inscripcion' => $this->datos_ciclo["inicio_inscripcion"],
                        'fin_inscripcion' => $this->datos_ciclo["fin_inscripcion"],
                        'inicio_repeticion' => $this->datos_ciclo["inicio_repeticion"],
                        'fin_repeticion' => $this->datos_ciclo["fin_repeticion"],
                        'inicio_reinscripcion' => $this->datos_ciclo["inicio_reinscripcion"],
                        'fin_reinscripcion' => $this->datos_ciclo["fin_reinscripcion"],
                    ]);
                    break;

                case 'P':
                    CiclosConfigModel::create([
                        'ciclo_esc_id' => $ciclo_id,
                        'p1' => $this->datos_ciclo["p1"],
                        'p2' => $this->datos_ciclo["p2"],
                        'p3' => $this->datos_ciclo["p3"],
                        'fin_p1' => $this->datos_ciclo["fin_p1"],
                        'fin_p2' => $this->datos_ciclo["fin_p2"],
                        'fin_p3' => $this->datos_ciclo["fin_p3"],
                        'inicio_repeticion' => $this->datos_ciclo["inicio_repeticion"],
                        'fin_repeticion' => $this->datos_ciclo["fin_repeticion"],
                        'inicio_reinscripcion' => $this->datos_ciclo["inicio_reinscripcion"],
                        'fin_reinscripcion' => $this->datos_ciclo["fin_reinscripcion"],
                    ]);
                    break;

                case 'V':
                    CiclosConfigModel::create([
                        'ciclo_esc_id' => $ciclo_id,
                        'p3' => $this->datos_ciclo["p3"],
                        'fin_p3' => $this->datos_ciclo["fin_p3"],
                    ]);
                    break;

                default:
                    # code...
                    break;
            }


        }
    }

}
