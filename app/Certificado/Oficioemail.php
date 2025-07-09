<?php
//ANA MOLINA 04/08/2024
namespace App\Certificado;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class Oficioemail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $file_aut=$this->mailData['fileaut_pdf'];
        $file_apo=$this->mailData['fileapo_pdf'];
        return $this->subject('Email de Bachilleresdesonora.edu.mx')
                    ->view('certificados.email.oficioemail') ->attach($file_aut)->attach($file_apo);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Oficio de Validación de Certificado de Estudios',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            //view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
