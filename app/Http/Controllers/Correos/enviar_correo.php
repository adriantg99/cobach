<?php

namespace App\Http\Controllers\Correos;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\AlumnoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class enviar_correo extends Controller
{
    public function enviarCorreo(){
        //$docentes_registrados = 
    }

    public function send()
    {
        $correos_alumnos = array(
            'adrian.torresg@bachilleresdesonora.edu.mx'   
            
        ); 

        $correosInstitucionales = AlumnoModel::whereIn('correo_institucional', $correos_alumnos)->get();

        

        foreach ($correosInstitucionales as $alumno) {
            $correoInstitucional = $alumno->correo_institucional;
            $nombre = $alumno->nombre;
            $apellidos = $alumno->apellidos;
    
            // Redirigir con un mensaje de éxito
            //cobach2024
            $contenidoCorreo = "
            Asunto: ¡Bienvenidos al Colegio de Bachilleres del estado de Sonora!
            Estimados estudiantes de nuevo ingreso,

            Es un placer darles la más cordial bienvenida a nuestra institución educativa. 
            En nombre de todo el equipo, quiero expresarles lo emocionados que estamos de tenerlos como parte de nuestra comunidad académica.
            Para comenzar esta emocionante etapa de sus vidas, les proporcionaremos algunas instrucciones importantes para activar y utilizar su correo electrónico institucional. 
            Este correo electrónico será una herramienta fundamental para mantenernos conectados y para acceder a recursos y comunicaciones importantes.

            Su correo institucional es: $correoInstitucional y su contraseña temporal es cobach2024.
            Recuerde que una vez iniciada sesión con la contraseña temporal se solicitara cambio de la misma.

            Una vez más, ¡bienvenidos a nuestra institución! Estamos emocionados de acompañarlos en este viaje académico y esperamos que su experiencia sea enriquecedora y llena de aprendizaje.";
            
            Mail::raw($contenidoCorreo, function($message) use ($correoInstitucional){
                $message->to($correoInstitucional)->subject('¡Bienvenidos al Colegio de Bachilleres del estado de Sonora!');
            });
        }

        return 'Correos electrónicos enviados correctamente.';

    }

}
