<?php

namespace App\Http\Controllers\Imagenes;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use Illuminate\Http\Request;

class ArchivosController extends Controller
{
    //
    public function mostrarArchivo($tipo_archivo, $alumno_id)
    {
        $archivo = ImagenesalumnoModel::where('tipo', $tipo_archivo)
            ->where('alumno_id', $alumno_id)->first();


        if (!$archivo) {
            abort(404);
        }

        $mime = $this->obtenerMimeType($archivo->filename); // Método para obtener el tipo MIME
        $contenido = $archivo->imagen; // El contenido de la imagen

        return response($contenido)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . $archivo->nombre . '"');
    }

    public function obtenerArchivo($tipo_archivo, $alumno_id)
    {
        $archivo = ImagenesalumnoModel::where('tipo', $tipo_archivo)
            ->where('alumno_id', $alumno_id)
            ->first();

        if (!$archivo) {
            return response()->json([
                'mensaje' => 'No encontrado',
            ], 404); // Devuelve un estado 404 y el mensaje
        }

        // Obtener MIME y codificar en Base64
        $mime = $this->obtenerMimeType($archivo->filename);
        $contenido = base64_encode($archivo->imagen);

        return response()->json([
            'mime' => $mime,
            'contenido' => $contenido,
            'nombre' => $archivo->nombre,
        ]);
    }

    public function determina_tipo($tipo_archivo, $alumno_id)
    {
        $archivo = ImagenesalumnoModel::where('tipo', $tipo_archivo)
            ->where('alumno_id', $alumno_id)->select('tipo', 'filename')->first();


        if (!$archivo) {
            abort(404);
        }

        $mime = $this->obtenerMimeType($archivo->filename); // Método para obtener el tipo MIME

        return response($mime);
    }

    // Método para determinar el tipo MIME basado en la extensión del archivo
    private function obtenerMimeType($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];


        return $mimeTypes[$ext] ?? 'application/octet-stream';
    }

    public function descargarArchivo($tipo_archivo, $alumno_id)
    {
        $archivo = ImagenesalumnoModel::where('tipo', $tipo_archivo)
            ->where('alumno_id', $alumno_id)
            ->first();

        if (!$archivo) {
            abort(404);
        }

        $contenido = $archivo->imagen;  // Contenido binario del archivo
        $mime = $this->obtenerMimeType($archivo->filename);



        switch ($tipo_archivo) {
            case '1':
                $tipo = "foto_boleta";
                break;

            case '2':
                $tipo = "foto_certificado";
                break;
            case '3':
                $tipo = "foto_boleta";
                break;
            case '4':
                $tipo = "Acta_nacimiento";
                break;
            case '5':
                $tipo = "Certificado_secundaria";
                break;
            case '6':
                $tipo = "Curp";
                break;




            default:
                $tipo = "Documento";
                break;
        }
        // Extraer la extensión del archivo
        $extension = pathinfo($archivo->filename, PATHINFO_EXTENSION);

        // Nombre final con extensión
        $nombreDescarga = $tipo . "_" . $archivo->no_expediente . '.' . $extension;
        return response($contenido)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'attachment; filename="' . $nombreDescarga . '"');
    }

}
