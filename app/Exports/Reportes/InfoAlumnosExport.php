<?php

namespace App\Exports\Reportes;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Grupos\GruposModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;



class InfoAlumnosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $plantel_seleccionado;
    protected $periodo_seleccionado;
    protected $grupo_seleccionar;
    protected $ciclo_activo;
    protected $plantel;


    public function __construct($plantel_seleccionado, $periodo_seleccionado, $grupo_seleccionar, $ciclo_activo, $plantel)
    {
        $this->plantel_seleccionado = $plantel_seleccionado;
        $this->periodo_seleccionado = $periodo_seleccionado;
        $this->grupo_seleccionar = $grupo_seleccionar;
        $this->ciclo_activo = $ciclo_activo;
        $this->plantel = $plantel;
    }

    public function collection()
    {
        $alumnos = collect();
        //dd($this->plantel_seleccionado, $this->periodo_seleccionado, $this->grupo_seleccionar);
        if ($this->plantel_seleccionado != 0) {
            if ($this->periodo_seleccionado != "" && $this->grupo_seleccionar != 0) {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.id', $this->grupo_seleccionar)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                    ->selectRaw("
        DISTINCT(alu_alumno.noexpediente), 
        alu_alumno.nombre, 
        alu_alumno.apellidopaterno, 
        alu_alumno.apellidomaterno, 
        alu_alumno.sexo,
        alu_alumno.correo_institucional, 
        alu_alumno.email, 
        alu_alumno.etnia_pertenece, 
        alu_alumno.fechanacimiento,  -- Faltaba una coma aquí
        alu_alumno.tiposangre,
        CONCAT(esc_grupo.nombre, 
            CASE esc_grupo.turno_id
                WHEN 1 THEN ' M'
                WHEN 2 THEN ' V'
            END
        ) AS grupo, 
        COALESCE(
            CONCAT(domicilio, ' ', domicilio_entrecalle, ' Numero: #', 
            COALESCE(domicilio_noexterior, domicilio_nointerior), ' Colonia: ', colonia), 
            CONCAT(tutor_domicilio, ' Colonia: ', tutor_colonia)
        ) AS domicilio, 
        servicio_medico_afiliacion, 
        curp, 
         CASE SUBSTRING(curp, 12, 2)
            WHEN 'NE' THEN 'NO ESPECIFICADO'
            WHEN 'AS' THEN 'AGUASCALIENTES'
            WHEN 'BC' THEN 'BAJA CALIFORNIA'
            WHEN 'BS' THEN 'BAJA CALIFORNIA SUR'
            WHEN 'CC' THEN 'CAMPECHE'
            WHEN 'CL' THEN 'COAHUILA DE ZARAGOZA'
            WHEN 'CM' THEN 'COLIMA'
            WHEN 'CS' THEN 'CHIAPAS'
            WHEN 'CH' THEN 'CHIHUAHUA'
            WHEN 'DF' THEN 'CIUDAD DE MÉXICO'
            WHEN 'DG' THEN 'DURANGO'
            WHEN 'GT' THEN 'GUANAJUATO'
            WHEN 'GR' THEN 'GUERRERO'
            WHEN 'HG' THEN 'HIDALGO'
            WHEN 'JC' THEN 'JALISCO'
            WHEN 'MC' THEN 'MÉXICO'
            WHEN 'MN' THEN 'MICHOACÁN DE OCAMPO'
            WHEN 'MS' THEN 'MORELOS'
            WHEN 'NT' THEN 'NAYARIT'
            WHEN 'NL' THEN 'NUEVO LEÓN'
            WHEN 'OC' THEN 'OAXACA'
            WHEN 'PL' THEN 'PUEBLA'
            WHEN 'QT' THEN 'QUERÉTARO'
            WHEN 'QR' THEN 'QUINTANA ROO'
            WHEN 'SP' THEN 'SAN LUIS POTOSÍ'
            WHEN 'SL' THEN 'SINALOA'
            WHEN 'SR' THEN 'SONORA'
            WHEN 'TC' THEN 'TABASCO'
            WHEN 'TS' THEN 'TAMAULIPAS'
            WHEN 'TL' THEN 'TLAXCALA'
            WHEN 'VZ' THEN 'VERACRUZ DE IGNACIO DE LA LLAVE'
            WHEN 'YN' THEN 'YUCATÁN'
            WHEN 'ZS' THEN 'ZACATECAS'
            WHEN 'NA' THEN 'NO APLICA'
            WHEN 'SI' THEN 'SE IGNORA'
            ELSE 'DESCONOCIDO'
        END AS estado_nacimiento,
        COALESCE(tutor_telefono, tutor_celular, familiar_celular),  observaciones, discapacidad_describe
    ")
                    ->orderBy('apellidopaterno', 'asc')
                    ->get();

                /*
                'No Expediente',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Semestre',
            'Grupo',
            'NSS',
            'CURP',
            'Telefono Tutor' */

                $nombre = GruposModel::where('id', $this->grupo_seleccionar)
                    ->selectRaw("CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                    WHEN 1 THEN ' M'
                    WHEN 2 THEN ' V'
                    END) AS grupo")
                    ->first();
                //$archivo = $this->plantel->nombre . '_' . $nombre->grupo;
            } elseif ($this->periodo_seleccionado != 0 && $this->grupo_seleccionar == 0) {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.periodo', $this->periodo_seleccionado)
                    ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->selectRaw("
                    DISTINCT(alu_alumno.noexpediente), 
                    alu_alumno.nombre, 
                    alu_alumno.apellidopaterno, 
                    alu_alumno.apellidomaterno, 
                    alu_alumno.sexo, 
                    alu_alumno.correo_institucional,  
                    alu_alumno.email, 
                    alu_alumno.etnia_pertenece, 
                    alu_alumno.fechanacimiento,  -- Aquí faltaba una coma
                    alu_alumno.tiposangre,
                    CONCAT(esc_grupo.nombre, 
                        CASE esc_grupo.turno_id
                            WHEN 1 THEN ' M'
                            WHEN 2 THEN ' V'
                        END
                    ) AS grupo, 
                    COALESCE(
                        CONCAT(domicilio, ' ', domicilio_entrecalle, ' Numero: #', 
                        COALESCE(domicilio_noexterior, domicilio_nointerior), ' Colonia: ', colonia), 
                        CONCAT(tutor_domicilio, ' Colonia: ', tutor_colonia)
                    ) AS domicilio, 
                    servicio_medico_afiliacion, 
                    curp, 
                     CASE SUBSTRING(curp, 12, 2)
            WHEN 'NE' THEN 'NO ESPECIFICADO'
            WHEN 'AS' THEN 'AGUASCALIENTES'
            WHEN 'BC' THEN 'BAJA CALIFORNIA'
            WHEN 'BS' THEN 'BAJA CALIFORNIA SUR'
            WHEN 'CC' THEN 'CAMPECHE'
            WHEN 'CL' THEN 'COAHUILA DE ZARAGOZA'
            WHEN 'CM' THEN 'COLIMA'
            WHEN 'CS' THEN 'CHIAPAS'
            WHEN 'CH' THEN 'CHIHUAHUA'
            WHEN 'DF' THEN 'CIUDAD DE MÉXICO'
            WHEN 'DG' THEN 'DURANGO'
            WHEN 'GT' THEN 'GUANAJUATO'
            WHEN 'GR' THEN 'GUERRERO'
            WHEN 'HG' THEN 'HIDALGO'
            WHEN 'JC' THEN 'JALISCO'
            WHEN 'MC' THEN 'MÉXICO'
            WHEN 'MN' THEN 'MICHOACÁN DE OCAMPO'
            WHEN 'MS' THEN 'MORELOS'
            WHEN 'NT' THEN 'NAYARIT'
            WHEN 'NL' THEN 'NUEVO LEÓN'
            WHEN 'OC' THEN 'OAXACA'
            WHEN 'PL' THEN 'PUEBLA'
            WHEN 'QT' THEN 'QUERÉTARO'
            WHEN 'QR' THEN 'QUINTANA ROO'
            WHEN 'SP' THEN 'SAN LUIS POTOSÍ'
            WHEN 'SL' THEN 'SINALOA'
            WHEN 'SR' THEN 'SONORA'
            WHEN 'TC' THEN 'TABASCO'
            WHEN 'TS' THEN 'TAMAULIPAS'
            WHEN 'TL' THEN 'TLAXCALA'
            WHEN 'VZ' THEN 'VERACRUZ DE IGNACIO DE LA LLAVE'
            WHEN 'YN' THEN 'YUCATÁN'
            WHEN 'ZS' THEN 'ZACATECAS'
            WHEN 'NA' THEN 'NO APLICA'
            WHEN 'SI' THEN 'SE IGNORA'
            ELSE 'DESCONOCIDO'
        END AS estado_nacimiento,
                    COALESCE(tutor_telefono, tutor_celular, familiar_celular),  observaciones, discapacidad_describe
                ")
                    ->orderBy('apellidopaterno', 'asc')
                    ->get();

                if ($alumnos->isEmpty()) {

                    $alumnos = AlumnoModel::where('plantel_id', $this->plantel_seleccionado)
                        ->where('cicloesc_id', $this->ciclo_activo->id)
                        ->selectRaw('noexpediente, nombre, apellidos, secundaria_promedio, observaciones, tutor_domicilio,tutor_colonia, tutor_telefono, 
                    discapacidad_describe, meds_permit, CONCAT(CASE alu_alumno.id_estatus WHEN 1 THEN "INSCRITO" ELSE "" END) as inscrito')
                        ->get();
                }

                //$archivo = $this->plantel->nombre . '_' . $this->periodo_seleccionado . '_Semestre';
            } elseif ($this->plantel_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->periodo_seleccionado == 0) {
                $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                    ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                    ->where('esc_grupo.plantel_id', $this->plantel_seleccionado)
                    ->where('ciclo_esc_id', $this->ciclo_activo->id)
                    ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                    ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                    ->selectRaw("DISTINCT(alu_alumno.noexpediente), alu_alumno.nombre, alu_alumno.apellidopaterno, alu_alumno.apellidomaterno, alu_alumno.sexo,
                    alu_alumno.correo_institucional, alu_alumno.email, alu_alumno.etnia_pertenece, alu_alumno.fechanacimiento, alu_alumno.tiposangre, esc_grupo.periodo,
                    CONCAT(esc_grupo.nombre, CASE esc_grupo.turno_id
                    WHEN 1 THEN ' M'
                    WHEN 2 THEN ' V'
                    END) AS grupo, COALESCE(
        CONCAT(domicilio, ' ', domicilio_entrecalle, ' Numero: #',coalesce(domicilio_noexterior, domicilio_nointerior), ' Colonia: ', colonia), 
        CONCAT(tutor_domicilio, ' Colonia: ', tutor_colonia)
    ) AS domicilio, servicio_medico_afiliacion, curp, 
              CASE SUBSTRING(curp, 12, 2)
            WHEN 'NE' THEN 'NO ESPECIFICADO'
            WHEN 'AS' THEN 'AGUASCALIENTES'
            WHEN 'BC' THEN 'BAJA CALIFORNIA'
            WHEN 'BS' THEN 'BAJA CALIFORNIA SUR'
            WHEN 'CC' THEN 'CAMPECHE'
            WHEN 'CL' THEN 'COAHUILA DE ZARAGOZA'
            WHEN 'CM' THEN 'COLIMA'
            WHEN 'CS' THEN 'CHIAPAS'
            WHEN 'CH' THEN 'CHIHUAHUA'
            WHEN 'DF' THEN 'CIUDAD DE MÉXICO'
            WHEN 'DG' THEN 'DURANGO'
            WHEN 'GT' THEN 'GUANAJUATO'
            WHEN 'GR' THEN 'GUERRERO'
            WHEN 'HG' THEN 'HIDALGO'
            WHEN 'JC' THEN 'JALISCO'
            WHEN 'MC' THEN 'MÉXICO'
            WHEN 'MN' THEN 'MICHOACÁN DE OCAMPO'
            WHEN 'MS' THEN 'MORELOS'
            WHEN 'NT' THEN 'NAYARIT'
            WHEN 'NL' THEN 'NUEVO LEÓN'
            WHEN 'OC' THEN 'OAXACA'
            WHEN 'PL' THEN 'PUEBLA'
            WHEN 'QT' THEN 'QUERÉTARO'
            WHEN 'QR' THEN 'QUINTANA ROO'
            WHEN 'SP' THEN 'SAN LUIS POTOSÍ'
            WHEN 'SL' THEN 'SINALOA'
            WHEN 'SR' THEN 'SONORA'
            WHEN 'TC' THEN 'TABASCO'
            WHEN 'TS' THEN 'TAMAULIPAS'
            WHEN 'TL' THEN 'TLAXCALA'
            WHEN 'VZ' THEN 'VERACRUZ DE IGNACIO DE LA LLAVE'
            WHEN 'YN' THEN 'YUCATÁN'
            WHEN 'ZS' THEN 'ZACATECAS'
            WHEN 'NA' THEN 'NO APLICA'
            WHEN 'SI' THEN 'SE IGNORA'
            ELSE 'DESCONOCIDO'
        END AS estado_nacimiento,
                    COALESCE(tutor_telefono, tutor_celular, familiar_celular),  observaciones, discapacidad_describe")
                    ->distinct('alu_alumno.id')
                    ->orderBy('periodo', 'asc')
                    ->orderBy('grupo', 'asc')
                    ->orderBy('apellidopaterno', 'asc')
                    ->get();

                //$archivo = $this->plantel->nombre;
            }
        } else {
            $alumnos = AlumnoModel::join('esc_grupo_alumno', 'alu_alumno.id', '=', 'esc_grupo_alumno.alumno_id')
                ->join('esc_grupo', 'esc_grupo_alumno.grupo_id', '=', 'esc_grupo.id')
                ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
                ->where('ciclo_esc_id', $this->ciclo_activo->id)
                ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
                ->where('esc_grupo.descripcion', '!=', 'turno_especial')
                ->where('esc_grupo.plantel_id', '!=', '34')
                ->where('esc_grupo.nombre', '!=', 'Migracion')
                ->selectRaw("DISTINCT(alu_alumno.noexpediente), alu_alumno.nombre, alu_alumno.apellidopaterno, alu_alumno.apellidomaterno, alu_alumno.sexo,
            alu_alumno.correo_institucional, alu_alumno.email, alu_alumno.fechanacimiento, alu_alumno.tiposangre, esc_grupo.periodo,
            esc_grupo.nombre AS grupo, (CASE esc_grupo.turno_id
            WHEN 1 THEN ' M'
            WHEN 2 THEN ' V'
            END) as turno, cat_plantel.nombre as plantel, COALESCE(
CONCAT(alu_alumno.domicilio, ' ', domicilio_entrecalle, ' Numero: #',coalesce(domicilio_noexterior, domicilio_nointerior), ' Colonia: ', colonia), 
CONCAT(tutor_domicilio, ' Colonia: ', tutor_colonia)
) AS domicilio, servicio_medico_afiliacion, curp, 
          CASE SUBSTRING(curp, 12, 2)
            WHEN 'NE' THEN 'NO ESPECIFICADO'
            WHEN 'AS' THEN 'AGUASCALIENTES'
            WHEN 'BC' THEN 'BAJA CALIFORNIA'
            WHEN 'BS' THEN 'BAJA CALIFORNIA SUR'
            WHEN 'CC' THEN 'CAMPECHE'
            WHEN 'CL' THEN 'COAHUILA DE ZARAGOZA'
            WHEN 'CM' THEN 'COLIMA'
            WHEN 'CS' THEN 'CHIAPAS'
            WHEN 'CH' THEN 'CHIHUAHUA'
            WHEN 'DF' THEN 'CIUDAD DE MÉXICO'
            WHEN 'DG' THEN 'DURANGO'
            WHEN 'GT' THEN 'GUANAJUATO'
            WHEN 'GR' THEN 'GUERRERO'
            WHEN 'HG' THEN 'HIDALGO'
            WHEN 'JC' THEN 'JALISCO'
            WHEN 'MC' THEN 'MÉXICO'
            WHEN 'MN' THEN 'MICHOACÁN DE OCAMPO'
            WHEN 'MS' THEN 'MORELOS'
            WHEN 'NT' THEN 'NAYARIT'
            WHEN 'NL' THEN 'NUEVO LEÓN'
            WHEN 'OC' THEN 'OAXACA'
            WHEN 'PL' THEN 'PUEBLA'
            WHEN 'QT' THEN 'QUERÉTARO'
            WHEN 'QR' THEN 'QUINTANA ROO'
            WHEN 'SP' THEN 'SAN LUIS POTOSÍ'
            WHEN 'SL' THEN 'SINALOA'
            WHEN 'SR' THEN 'SONORA'
            WHEN 'TC' THEN 'TABASCO'
            WHEN 'TS' THEN 'TAMAULIPAS'
            WHEN 'TL' THEN 'TLAXCALA'
            WHEN 'VZ' THEN 'VERACRUZ DE IGNACIO DE LA LLAVE'
            WHEN 'YN' THEN 'YUCATÁN'
            WHEN 'ZS' THEN 'ZACATECAS'
            WHEN 'NA' THEN 'NO APLICA'
            WHEN 'SI' THEN 'SE IGNORA'
            ELSE 'DESCONOCIDO'
        END AS estado_nacimiento,
            COALESCE(tutor_telefono, tutor_celular, familiar_celular),  observaciones, discapacidad_describe")
                ->distinct('alu_alumno.id')
                ->orderBy('plantel', 'asc')
                ->orderBy('periodo', 'asc')
                ->orderBy('grupo', 'asc')
                ->orderBy('apellidopaterno', 'asc')
                ->get();
                        //dd($query->toSql(), $query->getBindings());

        }

        return $alumnos;
    }

    public function startCell(): string
    {
        return 'A3'; // Configura el inicio de la tabla (fila y columna)
    }

    public function title(): string
    {
        return 'Promedios'; // Configura el título de la hoja Excel
    }
    public function columnFormats(): array
    {
        return [
            'A' => '0',
            'B' => '0',
            'C' => '0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ajustar el ancho automático de las columnas, excepto la columna G
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            if ($column !== 'J') {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $sheet->getColumnDimension('J')->setWidth(30);


        // Configurar alineación horizontal de todas las columnas
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getStyle($column)->getAlignment()->setHorizontal('left');
        }

        // Merge de celdas y estilos
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);

        $sheet->getStyle('A3:P3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        $sheet->setCellValue('A1', 'Colegio de Bachilleres del Estado de Sonora');
        $sheet->setCellValue('A2', 'PLANTEL: ' . $this->plantel->nombre);

        $highestRow = $sheet->getHighestRow();
        for ($row = 1; $row <= $highestRow; $row++) {
            $cell = $sheet->getCell('J' . $row);
            if ($cell->getValue() !== null) {
                $cleanedValue = trim($cell->getValue());
                $cell->setValue($cleanedValue);
            }
        }
        // Ajustar el texto en la columna J para que se ajuste a la celda
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);

        // Alinear el texto a la izquierda en la columna J
        $sheet->getStyle('J')->getAlignment()->setHorizontal('left');

        // Formato de la columna H como numérico sin decimales
        $sheet->getStyle('H')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

        // Puedes también ajustar el ancho de otras columnas si lo necesitas
        $sheet->getColumnDimension('I')->setAutoSize(true);

    }

    public function headings(): array
    {
        if ($this->plantel_seleccionado == 0) {
            return [
                'No Expediente',
                'Nombre',
                'Apellido Paterno',
                'Apellido Materno',
                'Genero',
                'Correo Institucional',
                'Correo Personal',
                'Fecha Nacimiento',
                'TIPO SANGRE',
                'SEMESTRE',
                'Grupo',
                'Turno',
                'PLANTEL',
                'Domicilio',
                'NSS',
                'CURP',
                'Estado de nacimiento',
                'Telefono Tutor'
            ];
        }
        if ($this->plantel_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->periodo_seleccionado == 1) {
            return [
                'No Expediente',
                'Nombre',
                'Apellido Paterno',
                'Apellido Materno',
                'Genero',
                'Correo Institucional',
                'Correo Personal',
                'Etnia',
                'Fecha Nacimiento',
                'TIPO SANGRE',
                'Grupo',
                'Domicilio',
                'NSS',
                'CURP',
                'Estado de nacimiento',
                'Telefono Tutor'
            ];
        }
        if ($this->plantel_seleccionado != 0 && $this->grupo_seleccionar == 0 && $this->periodo_seleccionado == 0) {
            return [
                'No Expediente',
                'Nombre',
                'Apellido Paterno',
                'Apellido Materno',
                'Genero',
                'Correo Institucional',
                'Correo Personal',
                'Etnia',
                'Fecha Nacimiento',
                'TIPO SANGRE',
                'Periodo',
                'Grupo',
                'Domicilio',
                'NSS',
                'CURP',
                'Estado de nacimiento',
                'Telefono Tutor'
            ];
        } else {
            return [
                'No Expediente',
                'Nombre',
                'Apellido Paterno',
                'Apellido Materno',
                'Genero',
                'Correo Institucional',
                'Correo Personal',
                'ETNIA',
                'FECHA NACIMIENTO',
                'TIPO SANGRE',
                'Grupo',
                'Domicilio',
                'NSS',
                'CURP',
                'Estado de nacimiento',
                'Telefono Tutor'
            ];
        }

    }
}
