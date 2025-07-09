<?php

namespace App\Http\Controllers\Archivos;

use App\Http\Controllers\Controller;
use App\Models\Certificados\CertificadoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ExploradorController extends Controller
{
    public function index(Request $request)
    {
        if ($this->valida_permisos() == 1) {
            $folder = 'certificados'; // Carpeta raíz
            $path = public_path($folder);
            $search = $request->query('search', ''); // Obtiene el término de búsqueda
    
            $files = $this->searchFiles($path, $search);
            $directories = $this->searchDirectories($path, $search);
    
            // Procesar los archivos para extraer los números
            $processedFiles = array_map(function ($file) {
                $filename = pathinfo($file, PATHINFO_FILENAME); // Obtén el nombre sin la extensión
                $folio = null;
    
                if (preg_match('/-(\d+)\./', $file, $matches)) {
                    $folio = $matches[1]; // Captura el número
                }

                $certificado = CertificadoModel::where('folio', $folio)->first();

                if($certificado->vigente == "1"){
                    $valida = "Vigente";
                }
                else{
                    $valida = "Cancelado";
                }
                return [
                    'path' => $file,
                    'name' => basename($file),
                    'validacion' => $valida, // Número extraído (puede ser null si no se encuentra)
                ];
            }, $files);
    
            return view('archivos.explorador', compact('processedFiles', 'directories', 'path', 'folder', 'search'));
        } else {
            return redirect('/');
        }
    }

    public function folder(Request $request)
    {
        $folder = urldecode($request->query('folder', 'certificados')); // Decodifica la carpeta
        $path = public_path($folder);
    
        if (!File::isDirectory($path)) {
            \Log::error('Directorio no encontrado: ' . $path);
            return redirect()->back()->with('error', 'Directory not found.');
        }
    
        $search = $request->query('search', ''); // Obtiene el término de búsqueda
    
        $files = $this->searchFiles($path, $search);
        $directories = $this->searchDirectories($path, $search);
    
        return view('archivos.explorador', compact('files', 'directories', 'path', 'folder', 'search'));
    }


    public function download(Request $request)
    {
        $file = urldecode($request->query('file')); // Decodifica el archivo
        $filePath = public_path($file);

        if (File::exists($filePath)) {
            \Log::info('Descargando archivo: ' . $filePath);
            return Response::download($filePath);
        } else {
            \Log::error('Archivo no encontrado: ' . $filePath);
            return redirect()->back()->with('error', 'File not found.');
        }
    }

    // Filtra archivos por nombre
    protected function searchFiles($path, $search)
    {
        $files = File::allFiles($path);
        
        // Filtrar archivos según el término de búsqueda
        if ($search) {
            $files = array_filter($files, function ($file) use ($search) {
                return stripos(basename($file), $search) !== false;
            });
        }
        
        // Limitar a 100 archivos
        $files = array_slice($files, 0, 300);
        
        return $files;
    }

    // Filtra directorios por nombre
    protected function searchDirectories($path, $search)
    {
        $directories = File::directories($path);
        if ($search) {
            $directories = array_filter($directories, function ($directory) use ($search) {
                return stripos(basename($directory), $search) !== false;
            });
        }
        return $directories;
    }
    function valida_permisos()
    {
        $roles = Auth()->user()->getRoleNames()->toArray();
        $todos_los_valores = 0;

        foreach ($roles as $role) {
            if ($role === "control_escolar") {
                $todos_los_valores = 1;
                break;
            } elseif (strpos($role, "control_escolar_") === 0) {
                $validaciones[] = substr($role, 16);
                $todos_los_valores = 2;
                continue;
            } else {
                continue;
            }
        }

        return $todos_los_valores;
    }
}
