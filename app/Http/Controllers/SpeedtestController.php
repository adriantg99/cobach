<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeedtestController extends Controller
{
    public function run()
    {
        // Ejecutar Speedtest y capturar la salida
        $speedtest_output = shell_exec('/usr/local/bin/speedtest --json 2>&1');
    
        // Verificar si hubo un error en la ejecución
        if ($speedtest_output === null) {
            return response()->json(['error' => 'Comando speedtest no se pudo ejecutar'], 500);
        }
    
        // Decodificar la salida JSON
        $results = json_decode($speedtest_output, true);
    
        // Verificar si el JSON es válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Salida JSON no válida: ' . json_last_error_msg()], 500);
        }
    
        // Devolver los resultados
        return response()->json($results);
    }
}
