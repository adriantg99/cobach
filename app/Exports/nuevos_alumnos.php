<?php

namespace App\Exports;

use App\Models\AlumnoModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Grupos\GruposModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class nuevos_alumnos implements FromView
{
    protected $alumnos_nuevos_plantel;
    protected $plantel_nombre;
    protected $logo;

    public function __construct($alumnos_nuevos_plantel, $plantel_nombre, $logo)
    {
        $this->alumnos_nuevos_plantel = $alumnos_nuevos_plantel;
        $this->plantel_nombre = $plantel_nombre;
        $this->logo = $logo;
    }

    public function view(): View
    {
        return view('exports.grupos.nuevos_grupos', [
            'alumnos_nuevos_plantel' => $this->alumnos_nuevos_plantel,
            'plantel_nombre' => $this->plantel_nombre,
            'logo' => $this->logo,
        ]);
    }
}
