<?php

namespace App\Mail\CorreosNuevoIngreso;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Mail;


class NuevosCorreos extends Mailable
{
    use Queueable, SerializesModels;

    public $alumno;
    public $instruccionesPath;

    /**
     * Crear una nueva instancia del correo.
     *
     * @return void
     */
    public function __construct($alumno, $instruccionesPath)
    {
        $this->alumno = $alumno;
        $this->instruccionesPath = $instruccionesPath;
    }

    /**
     * Construir el mensaje del correo.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenidos a COBACH')
                    ->view('emails.nuevosalumnos')
                    ->with('alumno', $this->alumno)
                    ->attach($this->instruccionesPath, [
                        'as' => 'Instrucciones Inscripcion.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
