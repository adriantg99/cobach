<?php

namespace App\Http\Controllers\Estadisticas;

use App\Http\Controllers\Controller;
use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Adminalumnos\GrupoAlumnoModel;
use App\Models\Adminalumnos\ImagenesalumnoModel;
use App\Models\Administracion\ConstanciasModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\Cursos\CursosModel;
use App\Models\Cursos\CursosOmitidosModel;
use App\Models\Documentos\FotosModel;
use App\Models\Escolares\CalificacionesModel;
use App\Models\Grupos\GruposModel;
use App\Models\Catalogos\CicloEscModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Codedge\Tcpdf\Facades\Tcpdf;

use App\PhpQRcode\QRcodigo;

class constanciasController extends Controller
{
  public function get_ciclos()
  {
    $fechaHoy = Carbon::now();
    // $fechaHoy='2024-05-01';
    //$ciclos = CicloEscModel::where('per_inicio', '<=', $fechaHoy)->where('per_final', '>=', $fechaHoy)->get();
       $activo = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('activo', '1')
            ->first();

        $anterior = CicloEscModel::select('id', 'nombre', 'per_inicio')
            ->where('id', '!=', $activo?->id)
            ->where('per_inicio', '<', $activo?->per_inicio)
            ->where('tipo', '!=', 'V') // Excluir ciclos cuyo tipo sea 'V'
            ->orderBy('per_inicio', 'desc')
            ->first();

        $ciclos = collect([$activo, $anterior])->filter();
    $idc = '';
    foreach ($ciclos as $cic) {
      if ($idc != '')
        $idc = $idc . ',';
      $idc = $cic->id;
    }
    $array = explode(",", $idc);
    return $array;
  }

  public function generarPDFcredencial()
  {
    
    //ver credencial
    // Obtener los datos que deseas mostrar en el PDF usando Eloquent
    //  dd($request->user_id);
    $user = Auth()->user();



    $datos_alumno = AlumnoModel::where('correo_institucional', $user->email)->first();
    $alumno = $datos_alumno->id;

    return self::generarcredencial($alumno);

  }
  /*
    public function generarcredencial($alum, $grupo_id = null)
    {

      $alumno = base64_decode($alum);

      $grupo = base64_decode($grupo_id);
      //$alumno = $alum;
      dd($alum, $alumno, $grupo_id);
      if ($alumno == 0) {
        $datos = GruposModel::select(
          'alu_alumno.id',
          'alu_alumno.nombre',
          'alu_alumno.apellidos',
          'alu_alumno.noexpediente',
          'cat_plantel.nombre as plantel',
          'cat_plantel.cct',
          'cat_plantel.director',
          'periodo',
          'per_inicio',
          'per_final'
        )
          ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
          ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
          ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
          ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
          ->where('esc_grupo_alumno.grupo_id', $grupo_id)
          ->orderBy('noexpediente', 'asc')
          ->orderBy('per_inicio', 'asc')
          ->distinct('noexpediente')
          ->get();
      } else {
        if ($alumno == null) {
          $alumno = $alum;
        }
        $idc = self::get_ciclos();
        ini_set('max_execution_time', 600); // 5 minutes

        //dd($alum,$alumno);
        $datos = GruposModel::select(
          'alu_alumno.id',
          'alu_alumno.nombre',
          'alu_alumno.apellidos',
          'alu_alumno.noexpediente',
          'cat_plantel.nombre as plantel',
          'cat_plantel.cct',
          'cat_plantel.director',
          'periodo',
          'per_inicio',
          'per_final'
        )
          ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
          ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
          ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
          ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
          ->where('alu_alumno.id', $alum)
          ->wherein('cat_ciclos_esc.id', $idc)
          ->orderBy('noexpediente', 'asc')
          ->orderBy('per_inicio', 'asc')
          ->distinct('noexpediente')
          ->get();
        //dd($datos->count());

        if ($datos->count() > 0) {
          $imagen_alumnos = ImagenesalumnoModel::where('alumno_id', $alum)->where('tipo', '1')->first();

        } else {

          $datos = GruposModel::select(
            'alu_alumno.id',
            'alu_alumno.nombre',
            'alu_alumno.apellidos',
            'alu_alumno.noexpediente',
            'cat_plantel.nombre as plantel',
            'cat_plantel.cct',
            'cat_plantel.director',
            'periodo',
            'per_inicio',
            'per_final'
          )
            ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
            ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
            ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
            ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
            ->where('alu_alumno.id', $alumno)
            ->wherein('cat_ciclos_esc.id', $idc)
            ->orderBy('noexpediente', 'asc')
            ->orderBy('per_inicio', 'asc')
            ->distinct('noexpediente')
            ->get();

          $imagen_alumnos = ImagenesalumnoModel::where('alumno_id', $alumno)->where('tipo', '1')->first();

        }

        $logo = public_path('images/logocobachchico.png');
        $imagen_frente = public_path('images/alumnos-frente.jpg');
        $imagen_atras = public_path('images/atras-alumnos.jpg');
        $imagen_alumno = public_path('images/23210102.jpg');
        $expediente = $datos[0]->noexpediente;
        $firma_director = $datos[0]->cct . '.png';
        $plantel = $datos[0]->plantel;

        $fecha_inicio = Carbon::parse($datos[0]->per_inicio);
        $fecha_fin = Carbon::parse($datos[0]->per_final);

        $annio_inicio = $fecha_inicio->year;
        $annio_fin = $fecha_fin->year;

        $mes_inicio = $fecha_inicio->locale('es')->monthName;
        $mes_fin = $fecha_fin->locale('es')->monthName;

        $codigoqr = self::get_codigoqr($datos[0]->apellidos, $datos[0]->noexpediente);





        $pdf = \PDF::loadView('estadisticas.documentos.credenciales', compact(
          'datos',
          'logo',
          'imagen_atras',
          'imagen_frente',
          'imagen_alumnos',
          'firma_director',
          'codigoqr',
          'mes_inicio',
          'annio_inicio',
          'mes_fin',
          'annio_fin'
        )); // Reemplaza tu_vista por el nombre de tu vista


        return $pdf->stream($plantel . ' ' . $expediente . '.pdf');
      }


    }*/

  public function generarcredencial($alum, $grupo_id = null)
  {
    $alumno = base64_decode($alum);
    $grupo = GruposModel::find($grupo_id) ?? null;
    $idc = self::get_ciclos();
    ini_set('max_execution_time', 600);
    $logo = public_path('images/logocobachchico.png');
    $imagen_frente = public_path('images/alumnos-frente.jpg');
    $imagen_atras = public_path('images/atras-alumnos.jpg');

    // Si viene un grupo, procesar todos los alumnos del grupo
    if ($alumno == 0 || $grupo_id != 0) {
      
      $alumnos = GrupoAlumnoModel::select(
        'esc_grupo_alumno.alumno_id as id',
        'alu_alumno.apellidos'
      )
        ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->where('esc_grupo_alumno.grupo_id', $grupo_id)
        ->orderBy('alu_alumno.apellidos', 'asc')
        ->distinct()
        ->pluck('alu_alumno.id');

      $html = '';
      foreach ($alumnos as $id_alumno) {
        $datos = GruposModel::select(
          'alu_alumno.id',
          'alu_alumno.nombre',
          'alu_alumno.apellidos',
          'alu_alumno.noexpediente',
          'cat_plantel.nombre as plantel',
          'cat_plantel.cct',
          'cat_plantel.director',
          'periodo',
          'per_inicio',
          'per_final'
        )
          ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
          ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
          ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
          ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
          ->where('alu_alumno.id', $id_alumno)
          ->whereIn('cat_ciclos_esc.id', $idc)
          ->orderBy('noexpediente', 'asc')
          ->orderBy('per_inicio', 'asc')
          ->get();
        if ($datos->isEmpty())
          continue;

        // Datos individuales
        $imagen_alumnos = ImagenesalumnoModel::where('alumno_id', $id_alumno)->where('tipo', '1')->first();
        $firma_director = $datos[0]->cct . '.png';
        $plantel = $datos[0]->plantel;
        $expediente = $datos[0]->noexpediente;

        $fecha_inicio = Carbon::parse($datos[0]->per_inicio);
        $fecha_fin = Carbon::parse($datos[0]->per_final);
        $mes_inicio = $fecha_inicio->locale('es')->monthName;
        $mes_fin = $fecha_fin->locale('es')->monthName;
        $annio_inicio = $fecha_inicio->year;
        $annio_fin = $fecha_fin->year;

        $codigoqr = self::get_codigoqr($datos[0]->apellidos, $datos[0]->noexpediente);
        // Renderizar vista y acumular en HTML
        $html .= view('estadisticas.documentos.credenciales', compact(
          'datos',
          'logo',
          'imagen_atras',
          'imagen_frente',
          'imagen_alumnos',
          'firma_director',
          'codigoqr',
          'mes_inicio',
          'annio_inicio',
          'mes_fin',
          'annio_fin',
          'grupo'
        ))->render();

        // Agrega salto de página entre alumnos
        //$html .= '<div style="page-break-after: always;"></div>';
      }

      // Generar un solo PDF con todo el contenido renderizado
      $pdf = \PDF::loadHTML($html);
      return $pdf->stream('Credenciales_' . now()->format('Ymd_His') . '.pdf');

    }
    // Caso: un solo alumno
    
    $datos = GruposModel::select(
      'alu_alumno.id',
      'alu_alumno.nombre',
      'alu_alumno.apellidos',
      'alu_alumno.noexpediente',
      'cat_plantel.nombre as plantel',
      'cat_plantel.cct',
      'cat_plantel.director',
      'periodo',
      'per_inicio',
      'per_final'
    )
      ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
      ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
      ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
      ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
      ->where('alu_alumno.id', $alumno)
      ->orWhere('alu_alumno.id', $alum) // Permitir búsqueda por expediente
      ->whereIn('cat_ciclos_esc.id', $idc)
      ->orderBy('per_inicio', 'asc')
      ->get();
      
    if ($datos->isEmpty()) {
      abort(404, 'Alumno no encontrado');
    }

    $imagen_alumnos = ImagenesalumnoModel::where(function($query) use ($alumno, $alum) {
      $query->where('alumno_id', $alumno)
          ->orWhere('alumno_id', $alum);
    })->where('tipo', '1')->first();
    
    $firma_director = $datos[0]->cct . '.png';
    $plantel = $datos[0]->plantel;
    $expediente = $datos[0]->noexpediente;

    $fecha_inicio = Carbon::parse($datos[0]->per_inicio);
    $fecha_fin = Carbon::parse($datos[0]->per_final);
    $mes_inicio = $fecha_inicio->locale('es')->monthName;
    $mes_fin = $fecha_fin->locale('es')->monthName;
    $annio_inicio = $fecha_inicio->year;
    $annio_fin = $fecha_fin->year;

    $codigoqr = self::get_codigoqr($datos[0]->apellidos, $datos[0]->noexpediente);

    $pdf = \PDF::loadView('estadisticas.documentos.credenciales', compact(
      'datos',
      'logo',
      'imagen_atras',
      'imagen_frente',
      'imagen_alumnos',
      'firma_director',
      'codigoqr',
      'mes_inicio',
      'annio_inicio',
      'mes_fin',
      'annio_fin',
      'grupo'
    ));

    return $pdf->stream($plantel . ' ' . $expediente . '.pdf');
  }

  public function credencialdigital()
  {
    //ver credencial
    // Obtener los datos que deseas mostrar en el PDF usando Eloquent
    //  dd($request->user_id);
    $user = Auth()->user();
    $idc = self::get_ciclos();

    $datos_alumno = AlumnoModel::where('correo_institucional', $user->email)->first();
    $alumno = $datos_alumno->id;
    
    ini_set('max_execution_time', 600); // 5 minutes
    $dato = GruposModel::select('alu_alumno.id', 'alu_alumno.nombre', 'alu_alumno.apellidos', 'noexpediente', 'cat_plantel.nombre as plantel', 'cat_plantel.cct', 'cat_plantel.director', 'periodo', 'per_inicio')
      ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
      ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
      ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
      ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
      ->where('alu_alumno.id', $alumno)
      //->where('cat_plantel.cct', '26ECB0018Y')
      ->wherein('cat_ciclos_esc.id', $idc)
      ->orderBy('noexpediente', 'asc')
      ->orderBy('per_inicio', 'asc')
      ->distinct('noexpediente')
      ->first(); // Reemplaza TuModelo por el nombre de tu modelo
    //dd($dato);
    $logo = asset('images/logocobachchico.png');
    $imagen_frente = asset('images/alumnos-frente.jpg');
    $imagen_atras = asset('images/atras-alumnos.jpg');
    $imagen_alumno = asset('images/23210102.jpg');

    if ($dato == null) {
      return redirect()->route('ingreso_alumno.iniciar_reinscripcion')->with('warning', 'El alumno no se encuentra inscrito en el ciclo escolar actual');
    }


    $firma_director = $dato->cct . '.png';
    $plantel = $dato->plantel;

    $annio = date('Y', strtotime($dato->per_inicio));

    $anniom1 = $annio + 1;
    $codigoqr = self::get_codigoqr($dato->apellidos, $dato->noexpediente);

    $imagen_alumnos = ImagenesalumnoModel::where('alumno_id', $alumno)->where('tipo', '1')->first();


    return view('estadisticas.documentos.credencialdigital', compact('dato', 'logo', 'imagen_atras', 'imagen_frente', 'imagen_alumnos', 'firma_director', 'codigoqr', 'annio', 'anniom1')); // Reemplaza tu_vista por el nombre de tu vista


  }

  public function validacredencial($apellidos, $noexpediente)
  {
    //request $request
    //$request->user_id
    //ver credencial
    // Obtener los datos que deseas mostrar en el PDF usando Eloquent
    //  dd($request->user_id);
    $idc = self::get_ciclos();

    $datos_alumno = AlumnoModel::where('noexpediente', $noexpediente)->where('apellidos', $apellidos)->first();
    $dato = null;
    $logo = null;
    $imagen_frente = null;
    $imagen_atras = null;
    $imagen_alumno = null;
    $imagen_alumnos = null;
    $firma_director = null;
    $message = '';
    $codigoqr = null;
    if (isset($datos_alumno)) {
      $alumno = $datos_alumno->id;
      ini_set('max_execution_time', 600); // 5 minutes
      $dato = GruposModel::select('alu_alumno.id', 'alu_alumno.nombre', 'alu_alumno.apellidos', 'noexpediente', 'cat_plantel.nombre as plantel', 'cat_plantel.cct', 'cat_plantel.director', 'periodo', 'per_inicio')
        ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
        ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
        ->where('alu_alumno.id', $alumno)
        //->where('cat_plantel.cct', '26ECB0018Y')
        ->wherein('cat_ciclos_esc.id', $idc)
        ->orderBy('noexpediente', 'asc')
        ->distinct('noexpediente')
        ->first(); // Reemplaza TuModelo por el nombre de tu modelo
      //dd($dato);

      $logo = asset('images/logocobachchico.png');
      $imagen_frente = asset('images/alumnos-frente.jpg');
      $imagen_atras = asset('images/atras-alumnos.jpg');
      $imagen_alumno = asset('images/23210102.jpg');
      $expediente = $dato->noexpediente;
      $firma_director = $dato->cct . '.png';
      $plantel = $dato->plantel;

      $annio = date('Y', strtotime($dato->per_inicio));

      $anniom1 = $annio + 1;
      $codigoqr = self::get_codigoqr($dato->apellidos, $dato->noexpediente);

      $imagen_alumnos = ImagenesalumnoModel::where('alumno_id', $alumno)->where('tipo', '1')->first();


    } else
      $message = 'ALUMNO INEXISTENTE';
    return view('estadisticas.documentos.validacredencial', compact('dato', 'logo', 'imagen_atras', 'imagen_frente', 'imagen_alumnos', 'firma_director', 'codigoqr', 'message', 'apellidos', 'noexpediente', 'annio', 'anniom1')); // Reemplaza tu_vista por el nombre de tu vista


  }
  public function credencialporalumno()
  {
    return view('estadisticas.credenciales.index');

  }
  public function generarPDFConstancia(request $request)
  {
    $fechaHoy = Carbon::now();

    $logo = public_path('images/logocobachchico.png');
    $promedio = $request->promedio;
    if ($request->user_id == 0) {
      $datos = GruposModel::select(
        'alu_alumno.nombre',
        'alu_alumno.apellidos',
        'alu_alumno.id as id_alumno',
        'noexpediente',
        'alu_alumno.curp',
        'cat_plantel.nombre as plantel',
        'cat_plantel.cct',
        'cat_plantel.director',
        'esc_grupo.nombre as grupo_nombre',
        'esc_grupo.periodo',
        'esc_grupo.turno_id',
        'cat_plantel.localidad',
        'cat_ciclos_esc.id as ciclo',
        'cat_plantel.id as plantel_id'
      )
        ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
        ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
        ->where('esc_grupo.id', $request->grupo_id)
        ->where('cat_plantel.id', '!=', '34')
        ->where('esc_grupo.nombre', '!=', "ActasExtemporaneas")
        //->where('cat_plantel.cct', '26ECB0024I')
        ->where('cat_ciclos_esc.id', '=', '250')
        ->where('alu_alumno.id_estatus', '1')
        ->orderByDesc('cat_ciclos_esc.id')
        ->orderBy('alu_alumno.apellidos')
        //->take(1)
        //dd($datos->toSql(), $datos->getBindings());
        ->get();

    } else {
      $datos = GruposModel::select(
        'alu_alumno.nombre',
        'alu_alumno.apellidos',
        'alu_alumno.id as id_alumno',
        'noexpediente',
        'alu_alumno.curp',
        'cat_plantel.nombre as plantel',
        'cat_plantel.cct',
        'cat_plantel.director',
        'esc_grupo.nombre as grupo_nombre',
        'esc_grupo.periodo',
        'esc_grupo.turno_id',
        'cat_plantel.localidad',
        'cat_ciclos_esc.id as ciclo',
        'cat_plantel.id as plantel_id'
      )
        ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
        ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
        ->where('alu_alumno.id', $request->user_id)
        ->where('cat_plantel.id', '!=', '34')
        ->where('esc_grupo.nombre', '!=', "ActasExtemporaneas")
        //->where('cat_plantel.cct', '26ECB0024I')
        ->where('cat_ciclos_esc.id', '=', '250')
        ->orderByDesc('cat_ciclos_esc.id')
        ->take(1)
        //dd($datos->toSql(), $datos->getBindings());

        ->get();

      //dd($datos);



    }
    foreach ($datos as $aux) {
      $validacion = $aux->cct;
      $firma_director = public_path('public/firmas/' . $aux->cct . '.png');
      $expediente = $aux->noexpediente;
      if (permisos() != 3) {
        $aux->qr = $this->qr_constancias($aux->noexpediente, $aux->curp, $aux->ciclo, $aux->id_alumno, $aux->plantel_id);
      }


      if ($aux->turno_id == "1") {
        $turno_nombre = "MATUTINO";
      } else {
        $turno_nombre = "VESPERTINO";
      }
      switch ($aux->periodo) {
        case '1':
          $semestre_texto = "PRIMER";
          break;
        case '2':
          $semestre_texto = "SEGUNDO";
          break;
        case '3':
          $semestre_texto = "TERCER";

          break;
        case '4':
          $semestre_texto = "CUARTO";
          break;
        case '5':
          $semestre_texto = "QUINTO";
          break;
        case '6':
          $semestre_texto = "SEXTO";
          break;
        default:
          break;
      }
    }

    // $imagen_alumnos = FotosModel::where('expediente', $expediente)->first();

    $nombreMes = $fechaHoy->locale('es')->monthName;

    // Formatear la fecha en texto
    $fechaTexto = "a los " . $fechaHoy->day . " días del mes de " . $nombreMes . " del año " . $fechaHoy->year . ".";

    // Imprimir la fecha en texto
    $pdf = \PDF::loadView('estadisticas.documentos.constancias', compact('logo', 'datos', 'fechaTexto', 'firma_director', 'promedio'));
    return $pdf->stream('Constancia de estudio_' . $expediente . '.pdf');



  }

  public function generar_pdf_boletas($alumno = null, $ciclo_esc, $plantel_id = null, $grupo_id = null)
  {
    $logo = public_path('images/logocobachchico.png');

    $fechaHoy = Carbon::now();
    $nombreMes = $fechaHoy->locale('es')->monthName;

    // Formatear la fecha en texto
    $fechaTexto = "A " . $fechaHoy->day . " DE " . $nombreMes . " DE " . $fechaHoy->year . ".";

    if ($alumno != 0) {
      $calificaciones = DB::select('CALL obtener_calificaciones(?, ?)', [$ciclo_esc, $alumno]);

      //dd($calificaciones);
      $datos_alumno = AlumnoModel::find($alumno);
      $imagen_alumnos_2 = FotosModel::where('expediente', $datos_alumno->noexpediente)->get();
      $datos_grupo_plantel_alumno = CursosModel::join('esc_grupo', 'esc_curso.grupo_id', '=', 'esc_grupo.id')
        ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
        ->where('esc_grupo.ciclo_esc_id', $ciclo_esc)
        ->where('esc_grupo_alumno.alumno_id', $datos_alumno->id)
        ->where('cat_plantel.id', '!=', '34')
        ->select('cat_plantel.nombre as plantel', 'cat_plantel.director', 'cat_plantel.cct', 'cat_plantel.localidad', 'cat_plantel.domicilio', 'esc_grupo.nombre as grupo', 'cat_ciclos_esc.nombre as Ciclo', 'esc_grupo.turno_id');

      $datos_grupo_plantel_alumno_gpo_base_1 = clone $datos_grupo_plantel_alumno;
      $datos_grupo_plantel_alumno_gpo_base_1 = $datos_grupo_plantel_alumno_gpo_base_1->where('esc_grupo.gpo_base', 1)->first();

      if (!$datos_grupo_plantel_alumno_gpo_base_1) {
        $datos_grupo_plantel_alumno = $datos_grupo_plantel_alumno->first();
      } else {
        $datos_grupo_plantel_alumno = $datos_grupo_plantel_alumno_gpo_base_1;
      }

      //dd($calificaciones);
      //return view('estadisticas.grupos.boletas', compact('calificaciones', 'datos_grupo_plantel_alumno', 'imagen_alumnos_2', 'logo', 'datos_alumno', 'ciclo_esc', 'fechaTexto'));
      $pdf = \PDF::loadView('estadisticas.grupos.boletas', compact('calificaciones', 'datos_grupo_plantel_alumno', 'imagen_alumnos_2', 'logo', 'datos_alumno', 'ciclo_esc', 'fechaTexto'));

      return $pdf->stream('Boleta.pdf');
    } else {

      $alumnos_en_plantel_periodo = DB::table('esc_grupo_alumno')
        ->join('esc_grupo', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->select('esc_grupo_alumno.alumno_id', 'alu_alumno.apellidos', 'esc_grupo.id as grupo_id')
        ->where('esc_grupo.ciclo_esc_id', $ciclo_esc)
        ->where('esc_grupo.plantel_id', $plantel_id)
        ->where('esc_grupo.id', $grupo_id)
        ->groupBy('esc_grupo_alumno.alumno_id', 'alu_alumno.apellidos', 'grupo_id')
        ->orderBy('alu_alumno.apellidos', 'asc')
        ->get();

      //   dd($alumnos_en_plantel_periodo);
      $buscar_cursos = CursosModel::where('grupo_id', $grupo_id)->get();

      //Recorre a los alumnos del grupo
      foreach ($alumnos_en_plantel_periodo as $alumnos_sin_depurar) {
        $buscar_alumnos_omitidos = CursosOmitidosModel::where('alumno_id', $alumnos_sin_depurar->alumno_id)->count();

        if ($buscar_alumnos_omitidos > 0) {
          //Recorre los cursos del grupo

          foreach ($buscar_cursos as $auxiliar) {

            $buscar_el_alumno_omitido = CursosOmitidosModel::where('alumno_id', $alumnos_sin_depurar->alumno_id)
              ->where('curso_id', $auxiliar->id)->first();

            if ($buscar_el_alumno_omitido) {
              $alumnos_en_plantel_periodo = $alumnos_en_plantel_periodo->reject(function ($alumno) use ($alumnos_sin_depurar) {
                return $alumno->alumno_id == $alumnos_sin_depurar->alumno_id;
              });

            } else {
              continue;
            }
          }
        } else {
          continue;
        }

      }
      $grupo = GruposModel::find($grupo_id);

      $plantel = PlantelesModel::find($plantel_id);
      if ($grupo->turno_id == 1) {
        $turno = "matutino";
      } else {
        $turno = "vespertino";
      }
      $pdf = \PDF::loadView('estadisticas.grupos.boletas_grupales', compact('logo', 'ciclo_esc', 'fechaTexto', 'alumnos_en_plantel_periodo'));
      return $pdf->download($plantel->nombre . '_boletas_' . $grupo->nombre . '_' . $turno . '.pdf');
    }


  }
  public static function get_codigoqr($apellidos, $expediente)
  {
    //CODIGO QR
    //include('App\PhpQRcode\phpqrcode.php');
    //$q=QRcodigo::prueba();
    // $codigoqr= QRcodigo::get_raw(strval($resulmater["folio"])); // creates code image and outputs it directly into browser


    //$codigoqr= QRcodigo::get_png($folio); // creates code image and outputs it directly into browser
    //$codigoqr= QRcodigo::get_text(strval($resulmater["folio"])); // creates code image and outputs it directly into browser

    // echo $codigoqr;

    ob_start();
    QRcodigo::get_png("https://sice.cobachsonora.edu.mx/validacredencial/" . $apellidos . "/" . $expediente);
    // QRcodigo::get_png("http://localhost:8000/validacredencial/".$apellidos."/".$expediente);
    $result_qr_content_in_png = ob_get_contents();
    ob_end_clean();
    // PHPQRCode change the content-type into image/png... we change it again into html
    $result_qr_content_in_base64 = base64_encode($result_qr_content_in_png);
    //'codigoqr.png'
    // dd($codigoqr);

    return $result_qr_content_in_base64;
  }


  public function validaconstancia($encryptedParams)
  {
    $decodedParams = json_decode(base64_decode($encryptedParams), true);
    if ($decodedParams == null) {
      $no_encontrada = true;
      return view('estadisticas.documentos.validaconstancias', compact('no_encontrada'));
    } else {
      $no_encontrada = false;
    }
    $validar_constancia = ConstanciasModel::where('noexpediente', $decodedParams['expediente'])
      ->where('curp', $decodedParams['curp'])
      ->where('ciclo_esc_id', $decodedParams['ciclo'])
      ->where('id', $decodedParams['id'])
      ->first();

    //dd($decodedParams['expediente'], $decodedParams['curp'], $decodedParams['ciclo']);

    if ($validar_constancia) {

      $buscar_alumno = AlumnoModel::find($validar_constancia->alumno_id);
      $buscar_ciclo = CicloEscModel::find($decodedParams['ciclo']);
      $buscar_plantel = PlantelesModel::find($decodedParams['plantel_id']);
      $imagen = ImagenesalumnoModel::where('alumno_id', $buscar_alumno->id)->where('tipo', '1')->first();

      $datos = GruposModel::select(
        'alu_alumno.nombre',
        'alu_alumno.apellidos',
        'alu_alumno.id as id_alumno',
        'noexpediente',
        'alu_alumno.curp',
        'cat_plantel.nombre as plantel',
        'cat_plantel.cct',
        'cat_plantel.director',
        'esc_grupo.nombre as grupo_nombre',
        'esc_grupo.periodo',
        'esc_grupo.turno_id',
        'cat_plantel.localidad',
        'cat_ciclos_esc.id as ciclo'
      )
        ->join('esc_grupo_alumno', 'esc_grupo.id', '=', 'esc_grupo_alumno.grupo_id')
        ->join('alu_alumno', 'esc_grupo_alumno.alumno_id', '=', 'alu_alumno.id')
        ->join('cat_ciclos_esc', 'esc_grupo.ciclo_esc_id', '=', 'cat_ciclos_esc.id')
        ->join('cat_plantel', 'esc_grupo.plantel_id', '=', 'cat_plantel.id')
        ->where('alu_alumno.id', $buscar_alumno->id)
        ->where('cat_plantel.id', '!=', '34')
        ->where('esc_grupo.nombre', '!=', "ActasExtemporaneas")
        //->where('cat_plantel.cct', '26ECB0024I')
        ->where('cat_ciclos_esc.id', '=', $buscar_ciclo->id)
        ->orderByDesc('cat_ciclos_esc.id')
        //dd($datos->toSql(), $datos->getBindings());

        ->first();
      return view('estadisticas.documentos.validaconstancias', compact('validar_constancia', 'buscar_alumno', 'buscar_ciclo', 'imagen', 'datos', 'no_encontrada', 'buscar_plantel'));
    } else {
      $no_encontrada = true;
      return view('estadisticas.documentos.validaconstancias', compact('no_encontrada'));
    }
    //return $decodedParams;
  }

  private function qr_constancias($expediente, $curp, $ciclo, $alumno_id, $plantel_id)
  {
    $buscar_constancia = ConstanciasModel::where('alumno_id', $alumno_id)
      ->where('ciclo_esc_id', $ciclo)
      ->where('noexpediente', $expediente)
      ->first();

    if ($buscar_constancia) {
      // No hacer nada si se encuentra la constancia
      // Datos ya existentes

      $buscar_constancia->updated_at = now();
      $buscar_constancia->save();

      ob_start();
      $encryptedParams = base64_encode(json_encode([
        'id' => $buscar_constancia->id,
        'expediente' => $buscar_constancia->noexpediente,
        'curp' => $buscar_constancia->curp,
        'plantel_id' => $buscar_constancia->plantel_id_emite,
        'ciclo' => $buscar_constancia->ciclo_esc_id
      ]));



    } else {
      // Registrar un nuevo valor si no se encuentra la constancia para validacion con QR
      $nueva_constancia = ConstanciasModel::create([
        'alumno_id' => $alumno_id,
        'curp' => $curp,
        'noexpediente' => $expediente,
        'plantel_id_emite' => $plantel_id,
        'ciclo_esc_id' => $ciclo,
      ]);


      ob_start();
      $encryptedParams = base64_encode(json_encode([
        'id' => $nueva_constancia->id,
        'expediente' => $expediente,
        'curp' => $curp,
        'plantel_id' => $plantel_id,
        'ciclo' => $ciclo
      ]));






    }

    QRcodigo::get_png("https://sice.cobachsonora.edu.mx/validaconstancia/" . $encryptedParams);
    //QRcodigo::get_png("https://sice.cobachsonora.edu.mx/validaconstancia/" . $expediente . "/" . $curp . "/" . $ciclo);
    $result_qr_content_in_png = ob_get_contents();
    ob_end_clean();

    $result_qr_content_in_png = base64_encode($result_qr_content_in_png);


    return $result_qr_content_in_png;

  }
}


