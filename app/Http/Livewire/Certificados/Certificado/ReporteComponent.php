<?php
// ANA MOLINA 07/03/2024
// ANA MOLINA modificación 11/04/2024
namespace App\Http\Livewire\Certificados\Certificado;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReporteComponent extends Component
{
    public $alumno_id;
    public $resulmater;
    public $calificacionescer;
    public $csd;
    public $numcsd;
    public $codigoqr;
    public $folio;
    public $alumno;
    public $datos;
    public $imagen_logo;
    public $imagen_firma_control_escolar;
    public $logo_foto;

    public function render()
    {
         return view('livewire.certificados.certificado.reporte-component');

    }


}
