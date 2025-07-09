<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\CiclosConfigModel;
use Illuminate\Http\Request;

class CiclosConfigController extends Controller
{
    public function show($id)
    {
        $config = CiclosConfigModel::find($id);

        if (!$config) {
            return response()->json(['message' => 'Configuración no encontrada'], 404);
        }

        return response()->json(['data' => $config], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ciclo_esc_id' => 'required|integer|exists:cat_ciclos,id',
            'p1' => 'nullable|date',
            'fin_p1' => 'nullable|date|after_or_equal:p1',
            'p2' => 'nullable|date',
            'fin_p2' => 'nullable|date|after_or_equal:p2',
            'p3' => 'nullable|date',
            'fin_p3' => 'nullable|date|after_or_equal:p3',
            'inicio_inscripcion' => 'nullable|date',
            'fin_inscripcion' => 'nullable|date|after_or_equal:inicio_inscripcion',
            'inicio_semestre' => 'nullable|date',
            'fin_semestre' => 'nullable|date|after_or_equal:inicio_semestre',
            'inicio_repeticion' => 'nullable|date',
            'fin_repeticion' => 'nullable|date|after_or_equal:inicio_repeticion',
        ]);

        $config = CiclosConfigModel::create($validated);

        return response()->json(['data' => $config], 201);
    }

    public function update(Request $request, $id)
    {
        $config = CiclosConfigModel::find($id);

        if (!$config) {
            return response()->json(['message' => 'Configuración no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ciclo_esc_id' => 'required|integer|exists:cat_ciclos,id',
            'p1' => 'nullable|date',
            'fin_p1' => 'nullable|date|after_or_equal:p1',
            'p2' => 'nullable|date',
            'fin_p2' => 'nullable|date|after_or_equal:p2',
            'p3' => 'nullable|date',
            'fin_p3' => 'nullable|date|after_or_equal:p3',
            'inicio_inscripcion' => 'nullable|date',
            'fin_inscripcion' => 'nullable|date|after_or_equal:inicio_inscripcion',
            'inicio_semestre' => 'nullable|date',
            'fin_semestre' => 'nullable|date|after_or_equal:inicio_semestre',
            'inicio_repeticion' => 'nullable|date',
            'fin_repeticion' => 'nullable|date|after_or_equal:inicio_repeticion',
        ]);

        $config->update($validated);

        return response()->json(['data' => $config], 200);
    }
}
