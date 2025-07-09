<?php

namespace App\Http\Controllers\Adminalumnos;

use App\Http\Controllers\Controller;
use App\Mail\CorreosNuevoIngreso\NuevosCorreos;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\CiclosConfigModel;
use App\Models\Cursos\CursosModel;
use App\Models\Finanzas\FichasModel;
use App\Models\Grupos\GruposModel;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use App\Mail\CorreosNuevoIngreso\NuevosCorreos;
use Illuminate\Support\Facades\Mail;
use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;



use PDF;


class IngresoAlumnoController extends Controller
{
    public function index()
    {
        return redirect('alumno/iniciar_reinscripcion');/*
        $alumno = AlumnoModel::where('correo_institucional', Auth()->user()->email)->first();

        $estados = DB::table('cat_localidades')->select(DB::raw('count(id) as locs, cve_ent, nom_ent'))
            ->groupBy('cve_ent', 'nom_ent')
            ->orderBy('cve_ent')
            ->get();

        if ($alumno->cicloesc_id = "248") {
            $reinscripcion = 1;
        }

        return view('adminalumnos.ingresoalumno.index', compact('alumno', 'estados', 'reinscripcion'));*/
    }

    public function aceptarCarta()
    {
        $alumno = AlumnoModel::where('correo_institucional', Auth()->user()->email)
            ->join('cat_plantel', 'alu_alumno.plantel_id', '=', 'cat_plantel.id')->first();

        if (is_null($alumno->carta_compromiso)) {
            DB::statement('UPDATE alu_alumno SET carta_compromiso = NOW() WHERE correo_institucional = ?', [Auth()->user()->email]);
        }


        $pdfPath = public_path('guia/Carta_compromiso.pdf');
        // Crear una instancia de FPDI que extiende TCPDF
        $pdf = new Fpdi();

        // Agregar una página
        $pdf->AddPage();

        // Cargar el PDF existente
        $pdf->setSourceFile($pdfPath);
        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId);

        // Configurar el estilo del texto
        $pdf->SetFont('Helvetica', '', 8);

        // Posicionar el texto en el PDF
        $pdf->SetXY(100, 34); // Ajusta la posición del texto
        $pdf->Write(0, $alumno->localidad);

        $timestamp = $alumno->carta_compromiso;
        $date = new DateTime(); // Fecha y hora actual
        $day = $date->format('d'); // Día
        //$month = $date->format('m'); // Mes
        $year = $date->format('Y'); // Año
        // Traducir el nombre del mes a español

        $monthName = $date->format('F'); // 'F' devuelve el nombre completo del mes en inglés

        $months = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        $monthNameInSpanish = $months[$monthName];
        // Escribir los valores en el PDF
        $pdf->SetXY(144, 34.5); // Ajusta la posición
        $pdf->Write(0, $day);


        $pdf->SetXY(150, 34.5); // Ajusta la posición
        $pdf->Write(0, "De " . $monthNameInSpanish);
        $pdf->SetXY(177, 34.4); // Ajusta la posición
        $pdf->Write(0, substr($year, 2));


        $pdf->SetXY(92, 230);
        if (is_null($alumno->carta_compromiso)) {
            $pdf->Write(0, Carbon::now());
        } else {
            $pdf->Write(0, $alumno->carta_compromiso);
        }

        //$pdf->Write(0, $alumno->carta_compromiso);


        // Guardar el PDF modificado en un archivo temporal
        $outputPath = storage_path('app/public/Carta_Compromiso.pdf');
        $pdf->Output($outputPath, 'F');

        // Forzar la descarga del archivo PDF modificado
        return response()->download($outputPath);


    }

    public function enviarCorreoBienvenida()
    {
        $alumnos = AlumnoModel::where('cicloesc_id', '248')
            ->where('email', '!=', null)
            ->where('noexpediente', '>', '24300084')
            //->where('id_periodo', '!=', '1')
            ->orderBy('noexpediente')
            ->distinct('email')
            ->get();

        $alumno_previo = "";

        try {
            $instruccionesPath = public_path('guia/guia.pdf'); // Ruta al archivo adjunto

            foreach ($alumnos as $alumno) {
                if (filter_var($alumno->email, FILTER_VALIDATE_EMAIL)) {
                    if ($alumno_previo == $alumno->email) {
                        continue;
                    } else {
                        $enviar_correo = Mail::to($alumno->email)->send(new NuevosCorreos($alumno, $instruccionesPath));

                        // Actualizar el campo id_periodo y guardar el cambio
                        $alumno->id_periodo = 1;
                        $alumno->save();
                        $alumno_previo = $alumno->email;
                    }

                } else {
                    \Log::info('Correo electrónico no válido: ' . $alumno->email);
                }

            }
        } catch (\Throwable $th) {
            // Manejar la excepción aquí, por ejemplo, puedes registrar el error
            \Log::error('Error enviando correo de bienvenida: ' . $th->getMessage());
            return 'Error enviando correos de bienvenida: ' . $th->getMessage();
        }


        return 'Correo de bienvenida enviado';
    }

    public function iniciarInscripcion(Request $request)
    {
        $fecha = Carbon::now();

        // Definir el inicio y el final del rango
        $startDate = Carbon::create(2024, 7, 29, 8, 1, 0); // 2024-07-29 08:01:00
        $endDate = Carbon::create(2024, 8, 5, 23, 59, 59); // 2024-08-05 23:59:59
        if ($fecha->greaterThanOrEqualTo($startDate) && $fecha->lessThanOrEqualTo($endDate)) {
            // La fecha está dentro del rango, realiza la acción que necesitas
                    //return redirect ('/speedtest');
        $alumno = AlumnoModel::where('folio_prepason', $request->input('folio_prepason'))->first();
        if ($alumno) {

        } else {
            return redirect('/login')->with('error', 'Datos con error');
        }
        $fecha = $alumno->fechanacimiento;
        $date = new DateTime($fecha);




        $fecha_formateada = $date->format('dmY'); // Esto da '08102009'
        
            if ($fecha_formateada == $request->input('password')) {
                $buscar_usuario = User::where('email', $alumno->correo_institucional)->first();
                if ($buscar_usuario) {
                    Auth::login($buscar_usuario);

                    if ($alumno->id_estatus == "1") {
                        return redirect('alumno/iniciar_reinscripcion');
                    } else {
                        $estados = DB::table('cat_localidades')->select(DB::raw('count(id) as locs, cve_ent, nom_ent'))
                            ->groupBy('cve_ent', 'nom_ent')
                            ->orderBy('cve_ent')
                            ->get();
                        return redirect('alumno/iniciar_reinscripcion');
                    }
                } else {
                    $new_user = User::create([
                        'name' => $alumno->nombre . $alumno->apellidos,
                        'email' => $alumno->correo_institucional,
                        'email_verified_at' => date("Y-m-d H:i:s"),
                        'password' => Hash::make('LTODO2023'),
                        'google_id' => "",
                        'google_picture' => "",
                    ]);
                    Auth::login($new_user);
                    Auth()->user()->assignRole('alumno');
                    return redirect('alumno/inform_personal');
                }


            } else {
                Auth::logout();

                return redirect('/login')->with('error', 'Datos con error');

                /*
                return response()->json([
                    'success' => false,
                    'message' => 'La fecha de nacimiento no es correcta.'
                ], 400); // Código de estado HTTP 400 (Solicitud incorrecta)*/
            }
        

        




        } else {
            // La fecha está fuera del rango
        }


    }

    public function inform_personal(Request $request, $alumno_id = null)
    {
        
        $alumno = AlumnoModel::where('correo_institucional', auth()->user()->email)->first();
    
        // Obtener estados de la base de datos
        $estados = DB::table('cat_localidades')
            ->select(DB::raw('count(id) as locs, cve_ent, nom_ent'))
            ->groupBy('cve_ent', 'nom_ent')
            ->orderBy('cve_ent')
            ->get();
            if ($alumno->id == 246223) {
                //return redirect('alumno/iniciar_reinscripcion');
                //Los alumnos pueden entrar a esta parte manualmente
                return view('adminalumnos.ingresoalumno.inform_personal', compact('alumno', 'estados'));

            }
        if (!$alumno && $alumno_id) {
            // Si no se encuentra el alumno por correo, busca por ID
            $alumno = AlumnoModel::find($alumno_id);
        }

        if ($alumno) {
            // Manejo del estatus del alumno si existe
            if ($alumno->id == 246223) {
                //return redirect('alumno/iniciar_reinscripcion');
                //Los alumnos pueden entrar a esta parte manualmente
                return view('adminalumnos.ingresoalumno.inform_personal', compact('alumno', 'estados'));

            } else {
                return redirect('alumno/iniciar_reinscripcion');
                //Los alumnos pueden entrar a esta parte manualmente
                // Aquí puedes manejar la ficha de pago o cualquier otra lógica
                //return view('adminalumnos.ingresoalumno.inform_personal', compact('alumno', 'estados'));
            }
        } else {
            // Aquí podrías manejar el caso donde no se encuentre ningún alumno
            // return redirect()->back()->with('error', 'Alumno no encontrado');
            abort(404, 'Alumno no encontrado');
        }
    }

    public function guardarDatos(Request $request)
    {

        $seccion = $request->input('seccion_actual');
        $alumnoId = $request->input('alumno_id');
    
        if ($alumnoId) {
            $alumno = AlumnoModel::find($alumnoId);
            if ($alumno) {
                // Procesar datos del alumno
                
            } else {
                return response()->json(['error' => 'Alumno no encontrado'], 404);
            }
        }
        else{
            $alumno = AlumnoModel::where('noexpediente', $request->input('no_expendiente'))->first();
            if (!$alumno) {
                return response()->json(['success' => false, 'message' => 'hubo fallo en la consulta']);
            }
        }
        

        switch ($seccion) {
            case 'datos_contacto':
                $request->validate([
                    'correo_electronico_personal' => 'required|max:300',
                    'telefono_contacto' => 'required|max:50',
                    'entidad_id' => 'required|numeric',
                    'municipio_id' => 'required|numeric',
                    'localidad_id' => 'required|numeric',
                ]);
                $alumno->email = $request->input('correo_electronico_personal');
                $alumno->celular = $request->input('telefono_contacto');
                $alumno->id_estadodomicilio = $request->input('entidad_id');
                $alumno->id_municipiodomicilio = $request->input('municipio_id');
                $alumno->id_localidaddomicilio = $request->input('localidad_id');
                //return response()->json(['success' => true, 'message' => 'sss'. $request->input('localidad_id')]);
                //dd($request->input('localidad_id'));
                $etniaCheck = $request->input('etnia_check', 0); // Si no está marcado, será 0
                if ($etniaCheck != 0) {
                    $alumno->id_etnia = '1';
                    $alumno->etnia_pertenece = $request->input('etnia_nombre');
                    if ($request->input('lengua_indigena_desc') != "") {
                        $alumno->lengua_indigena_desc = $request->input('lengua_indigena_desc');
                    }

                }
                $alumno->save();
                return response()->json(['success' => true, 'message' => 'Datos de contacto actualizados correctamente']);
            case 'tutor_familiar':
                $request->validate([
                    'nombre_tutor' => 'required|string|max:300',
                    'apellido_paterno_tutor' => 'required|string|max:150',
                    'apellido_materno_tutor' => 'required|string|max:150',
                    'correo_electronico_tutor' => 'required|string|max:100',
                    'telefono_tutor' => 'required|string|max:35',
                    'colonia_tutor' => 'required|string|max:50',
                    'domicilio_tutor' => 'required|string|max:100',
                    'nombre_familiar' => 'string|max:150|nullable',
                    'apellido_paterno_familiar' => 'string|max:150|nullable',
                    'apellido_materno_familiar' => 'string|max:150|nullable',
                    'correo_electronico_familiar' => 'string|max:150|nullable',
                    'telefono_familiar' => 'string|max:35|nullable',
                ]);

                $alumno->tutor_nombre = $request->input('nombre_tutor');
                $alumno->tutor_apellido1 = $request->input('apellido_paterno_tutor');
                $alumno->tutor_apellido2 = $request->input('apellido_materno_tutor');
                $alumno->tutor_telefono = $request->input('telefono_tutor');
                $alumno->tutor_colonia = $request->input('colonia_tutor');
                $alumno->tutor_domicilio = $request->input('domicilio_tutor');
                $alumno->tutor_email = $request->input('correo_electronico_tutor');

                if ($request->input('nombre_familiar') != '') {
                    $alumno->familiar_nombre = $request->input('nombre_familiar');
                    $alumno->familiar_apellido1 = $request->input('apellido_paterno_familiar');
                    $alumno->familiar_apellido2 = $request->input('apellido_materno_familiar');
                    $alumno->familiar_email = $request->input('correo_electronico_familiar');
                    $alumno->familiar_celular = $request->input('telefono_familiar');
                }

                $alumno->save();
                return response()->json(['success' => true, 'message' => 'Datos de Tutor/Familiar actualizado correctamente']);

            case 'docs':

                $alumno->servicio_medico_afiliacion = $request->input('file_nss');

                $rules = [
                    'file_acta' => 'required|file|mimes:pdf, jpg,jpeg,png|max:2048',
                    'file_certificado' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'file_foto' => 'required|file|mimes:jpg|max:2048',
                    'file_curp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ];
                // Validar la solicitud
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
                }

                $files = [
                    'file_acta' => 4,
                    'file_certificado' => 5,
                    'file_foto' => 1,
                    'file_curp' => 6
                ];
                $contador = 0;
                foreach ($files as $fileKey => $fileType) {
                    if ($request->hasFile($fileKey)) {
                        $buscar_doc = ImagenesalumnoModel::where('alumno_id', $alumno->id)->where('tipo', $fileType)->first();
                        $file = $request->file($fileKey);
                        $filePath = $file->getRealPath();

                        // Leer el archivo en modo binario
                        $fp = fopen($filePath, 'r+b');
                        $fileContents = fread($fp, filesize($filePath));
                        fclose($fp);

                        // Preparar los datos para guardar en la base de datos
                        $data = [
                            'imagen' => $fileContents,
                            'no_expediente' => $alumno->noexpediente,
                            'alumno_id' => $alumno->id,
                            'filename' => $fileKey . '_' . $alumno->noexpediente . '.' . $file->getClientOriginalExtension(), // Nombre del archivo
                            'filesize' => filesize($filePath),
                            'tipo' => $fileType, // Tipo de archivo
                        ];

                        // Guardar en la base de datos
                        if ($buscar_doc) {
                            $buscar_doc->update($data);
                            $contador += 1;
                            //$documento_guardado = $buscar_doc; // Asignar $buscar_documento a $documento_guardado para consistencia

                        } else {
                            $contador += 1;
                            $documento_guardado = ImagenesalumnoModel::create($data);
                        }



                    }

                }
                if ($contador == 4) {
                    $alumno->id_estatus = 1;
                    $alumno->save();

                    if($alumnoId){
                        return redirect('/');
                    }
                    else{
                        return redirect('alumno/iniciar_reinscripcion');
                    }
                    
                    //return response()->json(['success' => true, 'message' => "Archivo guardado: ".$documento_guardado->id]);
                } else {
                    return response()->json(['success' => false, 'message' => "Error al guardar el archivo"], 500);
                }
            /*
                            if ($request->hasFile('file_foto')) {
                                //return response()->json(['success' => false, 'message' => "ESTA PARTE".$request->file('file_foto')], 400);

                                $file = $request->file('file_foto');
                                $filePath = $file->getRealPath();

                                // Abrir el archivo en modo binario
                                $fp = fopen($filePath, 'r+b');
                                $fileContents = fread($fp, filesize($filePath));
                                fclose($fp);
                                // Obtener el número de expediente de alguna forma (ajusta esto según tu lógica)
                                // Obtener el registro del alumno
                                // Preparar los datos para guardar en la base de datos
                                $data = [
                                    'imagen' => $fileContents,
                                    'no_expediente' => $alumno->noexpediente,
                                    'alumno_id' => $alumno->id,
                                    'filename' => 'fotos_' . $alumno->noexpediente . '.' . $file->getClientOriginalExtension(), // Nombre del archivo
                                    'filesize' => filesize($filePath),
                                    'tipo' => "1", // Tipo de archivo, ajusta según tu necesidad
                                ];

                                $documento_guardado = ImagenesalumnoModel::create($data);
                                if ($documento_guardado) {
                                    //$this->reset(['file']);
                                    return response()->json(['success' => true, 'message' => "Foto guardada: ".$documento_guardado->id]);

                                    //return response()->json(['success' => false, 'message' => $documento_guardado->id], 400);

                                }
                            }
                            else{
                                return response()->json(['success' => false, 'message' => "no encontre foto"], 400);
                            }
            */


            default:
                return response()->json(['success' => false, 'message' => 'Volver a cargar los datos'], 400);
        }

    }

    public function carga_municipio(Request $request)
    {
        $municipios = DB::table('cat_localidades')->select(DB::raw('count(id) as muns, cve_ent, cve_mun, nom_mun'))
            ->groupBy('cve_ent', 'cve_mun', 'nom_mun')
            ->where('cve_ent', '26')
            ->orderBy('cve_mun')
            ->get();

            
        return view('adminalumnos.ingresoalumno.carga_municipio', compact('municipios'));
    }

    public function carga_localidad(Request $request)
    {
        $localidades = DB::table('cat_localidades')->select(DB::raw('count(id) as muns, cve_ent, cve_mun, cve_loc, nom_loc'))
            ->groupBy('cve_ent', 'cve_mun', 'cve_loc', 'nom_loc')
            ->where('cve_ent', $request->entidad_id)->where('cve_mun', $request->municipio_id)
            ->orderBy('cve_loc')
            ->get();
        return view('adminalumnos.ingresoalumno.carga_localidad', compact('localidades'));
    }


    public function imprimereferenciabancaria(Request $request)
    {
        $user = Auth()->user();
        $alumno = AlumnoModel::where('correo_institucional', $user->email)->first();

        $fichas = FichasModel::where('matricula', $alumno->noexpediente)->orderBy('total', 'DESC')->get();

        //dd($ficha);
        if (count($fichas) > 0) {
            foreach ($fichas as $ficha) {
                $ficha->generada = date("Y-m-d H:i:s");
                $ficha->update();
            }
        }

        //dd($request);
        $pdf = PDF::loadView('adminalumnos.ingresoalumno.pdf_papeletas.pdf_papeleta', compact('fichas'));

        return $pdf->download('FICHA_DEPOSITO_' . $alumno->noexpediente . '_' . date('YmdHis') . '.pdf');
    }

    public function consultaboleta(Request $request)
    {
        $user = Auth()->user();

        $datos_alumno = AlumnoModel::where('correo_institucional', $user->email)->first();
        $alumno = $datos_alumno->id;
        $ciclo_esc = GruposModel::join('esc_grupo_alumno','esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=','cat_ciclos_esc.id')
        ->where('esc_grupo_alumno.alumno_id', $datos_alumno->id)
        ->where('esc_grupo.nombre', '!=', 'ActasExtemporaneas')
        ->where('cat_ciclos_esc.id', '=', '250')
        ->select('cat_ciclos_esc.*')
        ->orderBy('cat_ciclos_esc.per_inicio', 'desc')
        ->first();

        $calificaciones = DB::select('CALL obtener_calificaciones(?, ?)', [$ciclo_esc->id, $alumno]);

        return view('adminalumnos.ingresoalumno.boleta', compact('alumno', 'calificaciones', 'ciclo_esc'));
    }

    public function consulta_grupo(){
        //echo "<script>alert('');</script>";
        //return redirect('/');
        return view('auth.login-consultagrupo');

    }

    public function consulta_grupo_post (Request $request)
    {        

        $sql = "SELECT * FROM tmp_alumnos_nuevo_ingreso WHERE noexpediente = '".$request->noexpediente."'";
        $data = DB::select($sql);
        if($data)
        {
            $alumno = $data[0];
        }
        else
        {
            $alumno = null;
        }        
        //$alumno = AlumnoModel::where('noexpediente', $request->noexpediente)->first();
        if($alumno)
        {
            //dd($alumno);
            if($alumno->fecha_nacimiento == $request->ano.'-'.$request->mes.'-'.$request->dia)
            {
                //dd($alumno);  
                /*
                $buscar_usuario = User::where('email', $alumno->correo_institucional)->first();
                if ($buscar_usuario)
                {
                    Auth::login($buscar_usuario);
                } 
                else 
                {
                    $new_user = User::create([
                        'name' => $alumno->nombre . $alumno->apellidos,
                        'email' => $alumno->correo_institucional,
                        'email_verified_at' => date("Y-m-d H:i:s"),
                        'password' => Hash::make('LTODO2023'),
                        'google_id' => "",
                        'google_picture' => "",
                    ]);
                    Auth::login($new_user);
                    Auth()->user()->assignRole('alumno');                            
                }
                    
                $sql = "SELECT esc_grupo.nombre AS grupo,  ";
                $sql = $sql."CASE turno_id WHEN 1 THEN 'MATUTINO' WHEN 2 THEN 'VESPERTINO' END AS turno, ";
                $sql = $sql."cat_plantel.nombre AS plantel, cat_plantel.id AS plantel_id ";
                $sql = $sql."FROM esc_grupo_alumno ";
                $sql = $sql."LEFT JOIN esc_grupo ON esc_grupo.id=esc_grupo_alumno.grupo_id ";
                $sql = $sql."LEFT JOIN cat_ciclos_esc ON cat_ciclos_esc.id=esc_grupo.ciclo_esc_id ";
                $sql = $sql."LEFT JOIN cat_plantel ON cat_plantel.ID=esc_grupo.plantel_id ";
                $sql = $sql."WHERE ((esc_grupo_alumno.alumno_id=".$alumno->id.") AND (cat_plantel.id <> 34) AND (esc_grupo.nombre <> 'ActasExtemporaneas') AND (esc_grupo.nombre <>'Migración') ) ";
                $sql = $sql."ORDER BY esc_grupo.nombre DESC ";
                //$sql = $sql."ORDER BY cat_ciclos_esc.per_inicio DESC "; //El que estaba antes 
                $sql = $sql."LIMIT 1";
                
                //dd($sql);
                $data = DB::select($sql);
                if($data)
                {
                    $datos=$data[0];
                }
                else
                {
                    $datos = null;
                }
                
                //dd($datos);
                //$grupo = GruposModel::where
                */

                return view('adminalumnos.ingresoalumno.consultagrupo', compact('alumno'));
            }
            else {
                try {
                    // Obtener el ID de la conexión actual
                    $currentConnectionId = DB::select("SELECT CONNECTION_ID() AS id")[0]->id;
            
                    // Terminar la conexión actual
                    DB::statement("KILL {$currentConnectionId}");
                } catch (\Exception $e) {
                    // Puedes ignorar la excepción causada por la terminación de la conexión
                    // ya que es un comportamiento esperado
                }
            
                // Redirigir a la página de logout
                return redirect('/consultagrupo')->with('error', 'Fecha de nacimiento incorrecta para el alumno '.$request->noexpediente);
            }
           
        }
        else
        {
            
            return redirect('/consultagrupo')->with('error', 'Favor de verificar más tarde');
            //return redirect('/consultagrupo')->with('error', 'Número de expediente '.$request->noexpediente.' no encontrado');
        }

    }

    public function kardex(){
        return view('adminalumnos.ingresoalumno.kardex_alumno');
    }

    public function getMunicipios($estado_id)
{
    return Municipio::where('cve_ent', $estado_id)->orderBy('nom_mun')->get();
}

public function getLocalidades($estado_id, $municipio_id)
{
    return Localidad::where('cve_ent', $estado_id)
        ->where('cve_mun', $municipio_id)
        ->orderBy('nom_loc')
        ->get();
}

}
