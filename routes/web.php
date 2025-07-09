<?php

use App\Http\Controllers\Adminalumnos\ActaExtemporaneaAlumnoController;
use App\Http\Controllers\Adminalumnos\AlumnoController;
use App\Http\Controllers\Adminalumnos\BoletaController;
use App\Http\Controllers\Adminalumnos\Calificaciones_por_alumno;
use App\Http\Controllers\Adminalumnos\Cursos_omitidosController;
use App\Http\Controllers\Adminalumnos\EquivalenciaController;
use App\Http\Controllers\Adminalumnos\ImagenAlumnoController;
use App\Http\Controllers\Adminalumnos\IngresoAlumnoController;
use App\Http\Controllers\Adminalumnos\KardexController;
use App\Http\Controllers\Adminalumnos\LoginUsuarios;
use App\Http\Controllers\Adminalumnos\MovimientosController;
use App\Http\Controllers\Adminalumnos\ReconocimientoCapacitacionController;
use App\Http\Controllers\Adminalumnos\TrasladosController;
use App\Http\Controllers\Adminalumnos\promocionAlumnos;
use App\Http\Controllers\Administracion\Acceso\RolController;
use App\Http\Controllers\Administracion\Acceso\UserController;
use App\Http\Controllers\Administracion\BitacoraController;
use App\Http\Controllers\Administracion\PerfilController;
use App\Http\Controllers\Api\Adminalumnos\AlumnoApiController;
use App\Http\Controllers\Archivos\ExploradorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuxscriptsController;
use App\Http\Controllers\Catalogos\AreaFormacionController;
use App\Http\Controllers\Catalogos\AsignaturaController;
use App\Http\Controllers\Catalogos\AulaController;
use App\Http\Controllers\Catalogos\CiclosEscController;
use App\Http\Controllers\Catalogos\DocentesController as DocentesCatalogosController;
use App\Http\Controllers\Catalogos\GeneralController;
use App\Http\Controllers\Catalogos\HoraPlantelController;
use App\Http\Controllers\Catalogos\NucleoController;
use App\Http\Controllers\Catalogos\PlandeEstudioController;
use App\Http\Controllers\Catalogos\PlantelController;
use App\Http\Controllers\Catalogos\PoliticaController;
use App\Http\Controllers\Catalogos\ReglamentoController;
use App\Http\Controllers\Certificados\CertificadoController;
use App\Http\Controllers\Certificados\OficioController;
use App\Http\Controllers\Certificados\VerificacertificadoController;
use App\Http\Controllers\Correos\enviar_correo;
use App\Http\Controllers\Cursos\MantenimientoController;
use App\Http\Controllers\Docentes\DocentesController;
use App\Http\Controllers\Docentes\aperturaDocentes;
use App\Http\Controllers\Estadisticas\CalificacionesController;
use App\Http\Controllers\Estadisticas\ExplorarController;
use App\Http\Controllers\Estadisticas\GruposController;
use App\Http\Controllers\Estadisticas\MatriculaController;
use App\Http\Controllers\Estadisticas\TableroController;
use App\Http\Controllers\Estadisticas\constanciasController;
use App\Http\Controllers\Examplereport1Controller;
use App\Http\Controllers\ExamplereportController;
use App\Http\Controllers\Grupos\crearGrupo;
use App\Http\Controllers\Imagenes\ArchivosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reportes\ReportesController;
use App\Http\Controllers\SpeedtestController;
use App\Http\Controllers\Testing\TestingController;
use App\Http\Controllers\estadisticas\calificaciones_ciclo;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//    return view('welcome');
// });

//LIGA PARA CONSULTA DE GRUPO Y TURNO
/*
Route::get('/consultagrupo', [IngresoAlumnoController::class, 'consulta_grupo'])->name('consulta_grupo');
Route::post('/consultagrupo', [IngresoAlumnoController::class, 'consulta_grupo_post'])->name('consulta_grupo_post');
*/

//LIGA PARA DESCARGA DE DIPLOMA DE CAPACITACION DESDE CORREO
Route::get('liga_externa/{liga}', function ($liga) {
    return redirect(base64_decode($liga));
    /*

    base64encode('/alumnos_egresados/diplomacap/228058') =

    Liga Encriptada para descarga de Diploma de capacitción

    $liga_encriptada = base64encode('/alumnos_egresados/diplomacap/'.$alumno->id);
    $liga_encriptada = aHR0cDovL3NjZS1jb2JhY2gudGVzdC9hbHVtbm9zX2VncmVzYWRvcy9kaXBsb21hY2FwLzIyODA1OA==

    //URL quedaria:
    URL('http://sce-cobach.test/liga_externa/'.$liga_encriptada);

    URL quedaria:
    http://sce-cobach.test/liga_externa/aHR0cDovL3NjZS1jb2JhY2gudGVzdC9hbHVtbm9zX2VncmVzYWRvcy9kaXBsb21hY2FwLzIyODA1OA==

    */
});

//Liga para validar credenciales sin iniciar sesión

Route::post('/nuevo_ingreso/iniciar_inscripcion', [IngresoAlumnoController::class, 'iniciarInscripcion'])->name('nuevo_ingreso.iniciar_inscripcion');

Route::get('alumnos_egresados/diplomacap/{alumno_id}', [ReconocimientoCapacitacionController::class, 'liga_diploma']);

Route::get('login/secreto/123', [AuthenticatedSessionController::class, 'create2']);

Route::get('/alumnos/kardex', [IngresoAlumnoController::class, 'kardex'])->name('kardex.alumno');


Route::get('login/google', [LoginController::class, 'redirectToProvider'])->name('login.google');
Route::get('google/callback', [LoginController::class, 'handleProviderCallback']);

//LS231031 Ingreso de Alumnos: Manejo de usuarios
Route::get('/', [LoginUsuarios::class, 'valida_usuario'])
    ->middleware(['auth', 'verified', 'bitacora'])
    ->name('dashboard');

Route::get('/send-emails', [enviar_correo::class, 'send']);
//LS231031 FIN Ingreso de Alumnos:

Route::middleware('auth')->group(function () {

    //--------I------------------------Portal Alumno--------------------------------------------------------------------------------------------------
    Route::group(['middleware' => ['count.connected.users:alumnos']], function () {
        //Rutas para validar la cantidad de alumnos conectados
        Route::get('alumno', [IngresoAlumnoController::class, 'index'])->name('ingreso_alumno.index');
        Route::get('alumno/inform_personal/{alumno_id?}', [IngresoAlumnoController::class, 'inform_personal'])->name('ingreso_alumno.inform_personal');

        Route::post('alumno/carta_compromiso', [IngresoAlumnoController::class, 'aceptarCarta'])->name('aceptar');



        Route::get('alumno/iniciar_reinscripcion', [LoginUsuarios::class, 'iniciar_reinscripcion'])->name('ingreso_alumno.iniciar_reinscripcion');
        Route::post('alumno/carga_municipio', [IngresoAlumnoController::class, 'carga_municipio'])->name('ingreso_alumno.carga_municipio');
        Route::post('alumno/carga_localidad', [IngresoAlumnoController::class, 'carga_localidad'])->name('ingreso_alumno.carga_localidad');
        Route::post('/alumno/guardar_datos', [IngresoAlumnoController::class, 'guardarDatos'])
            ->name('alumno.guardar_datos');

        Route::get('alumno/boleta', [IngresoAlumnoController::class, 'consultaboleta'])->name('ingreso_alumno.consultaboleta');

        Route::post('alumno/iniciar_reinscripcion/papeletapago', [IngresoAlumnoController::class, 'imprimereferenciabancaria'])->name('imprime_ref');

        //Route::post('/alumno/nuevo_ingreso', [IngresoAlumnoController::class, 'nuevos_alumnos'])->name('nuevo_ingreso.iniciar_inscripcion');


    });

    //--------F------------------------Portal Alumno--------------------------------------------------------------------------------------------------
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/logout', [ProfileController::class, 'logout'])->name('profile.logout');


    //USUARIOS
    Route::get('/usuarios', [UserController::class, 'index'])->name('user.index');
    Route::get('/usuarios/agregar', [UserController::class, 'agregar'])->name('user.agregar');
    Route::get('/usuarios/editar/{user_id}', [UserController::class, 'editar'])->name('user.editar');
    Route::get('/usuarios/eliminar/{user_id}', [UserController::class, 'eliminar'])->name('user.eliminar');

    Route::get('correos/enviar_nuevo_ingreso', [IngresoAlumnoController::class, 'enviarCorreoBienvenida'])->name('enviar_correos');

    //ROLES
    Route::get('/roles', [RolController::class, 'index'])->name('rol.index');
    Route::get('/roles/agregar', [RolController::class, 'agregar'])->name('rol.agregar');
    Route::get('/roles/editar/{rol_id}', [RolController::class, 'editar'])->name('rol.editar');
    Route::get('/roles/eliminar/{rol_id}', [RolController::class, 'eliminar'])->name('rol.eliminar');

    //BITACORA
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');

    route::get('/apertura', [aperturaDocentes::class, 'index'])->name('apertura.index');

    //Replicacion de calificaciones
    route::get('/replicacion', [aperturaDocentes::class, 'replicacion'])->name('replicacion.cursos');

    //PLANTELES
    Route::get('/catalogos/planteles', [PlantelController::class, 'index'])->name('catalogos.planteles.index');
    Route::get('/catalogos/planteles/agregar', [PlantelController::class, 'agregar'])->name('catalogos.planteles.agregar');
    Route::get('/catalogos/planteles/editar/{plantel_id}', [PlantelController::class, 'editar'])->name('catalogos.planteles.editar');
    Route::get('/catalogos/planteles/eliminar/{plantel_id}', [PlantelController::class, 'eliminar'])->name('catalogos.planteles.eliminar');
    Route::get('/catalogos/planteles/reporte', [PlantelController::class, 'reporte'])->name('catalogos.planteles.reporte');

    //CICLOS ESCOLARES 28jun23
    Route::get('/catalogos/ciclosesc', [CiclosEscController::class, 'index'])->name('catalogos.ciclosesc.index');
    Route::get('/catalogos/ciclosesc/agregar', [CiclosEscController::class, 'agregar'])->name('catalogos.ciclosesc.agregar');
    Route::get('/catalogos/ciclosesc/editar/{ciclo_esc_id?}', [CiclosEscController::class, 'editar'])->name('catalogos.ciclosesc.editar');
    Route::get('/catalogos/ciclosesc/agregar/success/{id}', [CiclosEscController::class, 'agregarsuccess'])->name('catalogos.ciclosesc.agregarsuccess');
    Route::get('/catalogos/ciclosesc/eliminar/{id}', [CiclosEscController::class, 'eliminar'])->name('catalogos.ciclosesc.eliminar');

    //AREAS DE FORMACIÓN
    Route::get('/catalogos/areasformacion', [AreaFormacionController::class, 'index'])->name('catalogos.areasformacion.index');
    Route::get('/catalogos/areasformacion/agregar', [AreaFormacionController::class, 'agregar'])->name('catalogos.areasformacion.agregar');
    Route::get('/catalogos/areasformacion/editar/{areaformacion_id}', [AreaFormacionController::class, 'editar'])->name('catalogos.areasformacion.editar');
    Route::get('/catalogos/areasformacion/eliminar/{areaformacion_id}', [AreaFormacionController::class, 'eliminar'])->name('catalogos.areasformacion.eliminar');

    //NUCLEOS
    Route::get('/catalogos/nucleos', [NucleoController::class, 'index'])->name('catalogos.nucleos.index');
    //Route::get('/catalogos/nucleos/agregar', [NucleoController::class, 'agregar'])->name('catalogos.nucleos.agregar');
    //Route::get('/catalogos/nucleos/editar/{nucleo_id}', [NucleoController::class, 'editar'])->name('catalogos.nucleos.editar');
    //Route::get('/catalogos/nucleos/eliminar/{nucleo_id}', [NucleoController::class, 'eliminar'])->name('catalogos.nucleos.eliminar');

    //ASIGNATURAS
    Route::get('/catalogos/asignaturas', [AsignaturaController::class, 'index'])->name('catalogos.asignaturas.index');
    Route::get('/catalogos/asignaturas/agregar', [AsignaturaController::class, 'agregar'])->name('catalogos.asignaturas.agregar');
    Route::get('/catalogos/asignaturas/editar/{asignatura_id}', [AsignaturaController::class, 'editar'])->name('catalogos.asignaturas.editar');
    Route::get('/catalogos/asignaturas/eliminar/{asignatura_id}', [AsignaturaController::class, 'eliminar'])->name('catalogos.asignaturas.eliminar');
    Route::get('/catalogos/asignaturas/exportar', [AsignaturaController::class, 'exportar'])->name('catalogos.asignatura.exportar');

    //POLITICAS
    Route::get('/catalogos/politicas', [PoliticaController::class, 'index'])->name('catalogos.politicas.index');
    Route::get('/catalogos/politicas/agregar', [PoliticaController::class, 'agregar'])->name('catalogos.politicas.agregar');
    Route::get('/catalogos/politicas/editar/{politica_id}', [PoliticaController::class, 'editar'])->name('catalogos.politicas.editar');
    Route::get('/catalogos/politicas/eliminar/{politica_id}', [PoliticaController::class, 'eliminar'])->name('catalogos.politicas.eliminar');
    Route::get('/catalogos/politicas/formula/{politica_id}', [PoliticaController::class, 'formula'])->name('catalogos.politicas.formula');

    //REGLAMENTOS
    Route::get('/catalogos/reglamentos', [ReglamentoController::class, 'index'])->name('catalogos.reglamentos.index');
    Route::get('/catalogos/reglamentos/agregar', [ReglamentoController::class, 'agregar'])->name('catalogos.reglamentos.agregar');
    Route::get('/catalogos/reglamentos/editar/{reglamento_id}', [ReglamentoController::class, 'editar'])->name('catalogos.reglamentos.editar');
    Route::get('/catalogos/reglamentos/eliminar/{reglamento_id}', [ReglamentoController::class, 'eliminar'])->name('catalogos.reglamentos.eliminar');

    //PLANES DE ESTUDIO
    Route::get('/catalogos/planesdeestudio', [PlandeEstudioController::class, 'index'])->name('catalogos.planesdeestudio.index');
    Route::get('/catalogos/planesdeestudio/agregar', [PlandeEstudioController::class, 'agregar'])->name('catalogos.planesdeestudio.agregar');
    Route::get('/catalogos/planesdeestudio/editar/{plan_id}', [PlandeEstudioController::class, 'editar'])->name('catalogos.planesdeestudio.editar');
    Route::get('/catalogos/planesdeestudio/eliminar/{plan_id}', [PlandeEstudioController::class, 'eliminar'])->name('catalogos.planesdeestudio.eliminar');
    Route::get('/catalogos/planesdeestudio/reporte', [PlandeEstudioController::class, 'reporte'])->name('catalogos.planesdeestudio.reporte');

    //ALUMNOS
    Route::get('/adminalumnos/alumnos', [AlumnoController::class, 'index'])->name('adminalumnos.alumnos.index');
    Route::get('/adminalumnos/alumnos/agregar/{id_plantel}', [AlumnoController::class, 'agregar'])->name('adminalumnos.alumnos.agregar');
    Route::get('/adminalumnos/alumnos/editar/{alumno_id?}', [AlumnoController::class, 'editar'])->name('adminalumnos.alumnos.editar');
    Route::get('/adminalumnos/alumnos/agregar/success/{id}', [AlumnoController::class, 'agregarsuccess'])->name('adminalumnos.alumnos.agregarsuccess');
    //Route::get('/adminalumnos/alumnos/credenciales/{user_id}', [constanciasController::class, 'generarPDFcredencial'])->name('user.credenciales');

    Route::get('alumno/credencial', [constanciasController::class, 'generarPDFcredencial'])->name('alumno.credencial');
    Route::get('alumno/credencial/{alumno}/{grupo_id?}', [constanciasController::class, 'generarcredencial'])->name('alumno.credencial.individual');
    Route::get('alumno/credencialdigital', [constanciasController::class, 'credencialdigital'])->name('alumno.credencialdigital');
    //La liga para validar se requiere sin inicio de sesión
    Route::get('/validacredencial/{apellidos}/{noexpediente}', [constanciasController::class, 'validacredencial'])->name('validacredencial');
    Route::get('alumno/credencialporalumno', [constanciasController::class, 'credencialporalumno'])->name('alumno.credencialporalumno');

    Route::get('/adminalumnos/alumnos/constancias/{user_id}/{grupo_id?}/{promedio?}', [constanciasController::class, 'generarPDFConstancia'])->name('adminalumnos.generarConstancias');
    Route::get('/adminalumnos/alumnos/boletas/{alumno_id?}/{ciclo_esc}/{plantel_id?}/{grupo_id?}', [constanciasController::class, 'generar_pdf_boletas'])->name('adminalumnos.generarboletas');

    //Cambio de plan de estudios para alumnos individuales
    Route::get('/adminalumnos/planestudio', [AlumnoController::class, 'cambio_plan'])->name('adminalumnos.cambio_plan');

    Route::get('/adminalumnos/nuevos_alumnos', [AlumnoController::class, 'nuevos_alumnos'])->name('adminalumnos.nuevos_alumnos');

    Route::get('/descargar/{alumno_id}', [AlumnoController::class, 'descargar'])->name('descargar.evidencia');


    Route::get('/nuevos_alumnos', [AlumnoController::class, 'cargar_nuevos_alumnos'])->name('adminalumnos.cargar_nuevos_alumnos');

    Route::get('/alumno/{id_alumno}/datos', [AlumnoController::class, 'datos']);

    Route::get('/adminalumnos/alumnos/cursos_omitidos', [Cursos_omitidosController::class, 'index'])->name('adminalumnos.cursos_omitidos');
    Route::post('/adminalumnos/alumnos/cursos_omitidos', [Cursos_omitidosController::class, 'index_post'])->name('adminalumnos.cursos_omitidos_post');



    //Actas Extemporaneas
    Route::get('/adminalumnos/alumnos/actas_ext/sel_plantel', [ActaExtemporaneaAlumnoController::class, 'busqueda_plantel'])
        ->name('adminalumnos.actas_ext.busqueda_plantel');
    Route::post('/adminalumnos/alumnos/actas_ext/sel_plantel_post', [ActaExtemporaneaAlumnoController::class, 'busqueda_plantel_post'])
        ->name('adminalumnos.actas_ext.busqueda_plante_post');
    Route::post('/adminalumnos/alumnos/actas_ext/alumno', [ActaExtemporaneaAlumnoController::class, 'alumno'])
        ->name('adminalumnos.actas_ext.alumno');
    Route::get('adminalumnos.actas_ext/{plantel_id}/list', [ActaExtemporaneaAlumnoController::class, 'listado_actas'])
        ->name('adminalumnos.actas_ext.list');
    Route::get('adminalumnos/alumnos/{act_id}/acta', [ActaExtemporaneaAlumnoController::class, 'imprime_acta'])
        ->name('adminalumnos.actas_ext.imprime');


    //GRUPOS Adrian Torres2023-10-10
    Route::get('/grupos/grupos', [crearGrupo::class, 'index'])->name('Grupos.crear.index');
    Route::get('/grupos/createGrupo', [crearGrupo::class, 'create'])->name('grupos.crear');
    Route::get('/grupos/update/{grupo_id}', [crearGrupo::class, 'update'])->name('grupos.update');
    Route::get('/grupos/eliminar/{grupo_id}', [crearGrupo::class, 'eliminar'])->name('grupos.eliminar');
    Route::get('/calificacion_alumno/{id_alumno}/{ciclo_esc_id}', [Calificaciones_por_alumno::class, 'getCalificacionAlumno'])->name('grupos.calificacion');

    //Promocion Alumnos
    Route::get('/alumnos/promocion', [PromocionAlumnos::class, 'index'])->name('alumnos.promocion');

    //Docentes
    Route::get('/docentes/index', [DocentesController::class, 'index'])->name('docentes.index');
    Route::post('/docentes/curso/{curso_id}/politica_variable/{politica_variable_id}', [DocentesController::class, 'imprime_calificaciones_curso'])
        ->name('docente.imprime_calificaciones_curso');
    Route::get('/docentes/actas', [DocentesController::class, 'actas'])->name('docentes.actas');
    Route::post('/docentes/lista_asistencia/{curso_id}/{parcial?}', [DocentesController::class, 'generar_lista'])->name('docentes.asistencia');

    //AULAS LS2023-10-05
    Route::get('/catalogos/plantel/{plantel_id}/aulas', [AulaController::class, 'aulas_del_plantel'])->name('catalogos.plantel.aulas');
    Route::get('/catalogos/plantel/{plantel_id}/aulas/agregar', [AulaController::class, 'agregar_al_plantel'])->name('catalogos.plantel.aulas.agregar');
    Route::get('/catalogos/plantel/aulas/{aula_id}/editar', [AulaController::class, 'editar_aula'])->name('catalogos.plantel.aulas.editar');
    Route::get('/catalogos/plantel/aulas/{aula_id}/eliminar', [AulaController::class, 'eliminar_aula'])->name('catalogos.plantel.aulas.eliminar');
    Route::get('/catalogos/plantel/{plantel_id}/aulas_excel', [AulaController::class, 'excel_plantel'])->name('catalogos.plantel.aulas_excel');

    //HORAS PLANTEL
    Route::get('/catalogos/plantel/{plantel_id}/horas', [HoraPlantelController::class, 'horas_del_plantel'])->name('catalogos.plantel.horas');

    //PERFIL LS2023-10-29
    Route::get('perfil', [PerfilController::class, 'perfil'])->name('perfil');

    //DOCENTES
    Route::get('administracion/docentes', [DocentesCatalogosController::class, 'index'])->name('adm.docentes');
    Route::post('administracion/docentes/plantel', [DocentesCatalogosController::class, 'index_post'])->name('adm.docentes.post');

    //KARDEX
    Route::get('/adminalumnos/consultacalificaciones/{expediente}', [KardexController::class, 'calificaciones'])->name('adminalumnos.consultacalificaciones');
    Route::get('/adminalumnos/kardex', [KardexController::class, 'index'])->name('adminalumnos.kardex.index');

    Route::get('/adminalumnos/kardex/reporte/{alumno_id?}/{grupo_id?}', [KardexController::class, 'reporte'])->name('adminalumnos.kardex.reporte');

    Route::get('/adminalumnos/kardex/reporte_hist_academ/{alumno_id?}/{grupo_id?}', [KardexController::class, 'reporte_hist_academ'])->name('adminalumnos.kardex.reporte_hist_academ');

    //IMAGEN ALUMNO
    Route::get('adminalumnos/imagenalumno', [ImagenAlumnoController::class, 'index'])->name('adminalumnos.imagenalumno');

    //CERTIFICADO
    Route::get('/certificados/certificado', [CertificadoController::class, 'index'])->name('certificados.certificado.index');
    Route::get('/certificados/certificado/reporte/{alumno_id}', [CertificadoController::class, 'reporte'])->name('certificados.certificado.reporte');
    Route::get('/certificados/certificado/reportegrupo/{alumnos_sel}/{grupo_id}', [CertificadoController::class, 'reportegrupo'])
        ->name('certificados.certificado.reportegrupo');

    Route::get('/certificados/revisa', [CertificadoController::class, 'revisar'])->name('certificados.revisa.index');
    //Link que lleva al prceso de revision
    Route::get('/certificados/certificado/revisagrupo/{alumnos_sel}/{grupo_id}', [CertificadoController::class, 'revisagrupo'])
        ->name('certificados.certificado.revisagrupo');
    Route::get('/certificados/revisa/revisalistado/{grupo_id}', [CertificadoController::class, 'revisalistado'])
        ->name('certificados.revisa.revisalistado');

    Route::get('/certificados/genera', [CertificadoController::class, 'generar'])->name('certificados.genera.index');
    Route::get('/certificados/genera/alumno{alumno_id}', [CertificadoController::class, 'alumno'])->name('certificados.genera.alumno');
    Route::get('/certificados/genera/grupo/{alumnos_sel}/{grupo_id}', [CertificadoController::class, 'grupo'])
        ->name('certificados.genera.grupo');

    Route::get('/certificados/cancela', [CertificadoController::class, 'cancelar'])->name('certificados.cancela.index');
    Route::get('/certificados/cancela/cancelagrupo/{alumnos_sel}/{grupo_id}', [CertificadoController::class, 'cancelagrupo'])
        ->name('certificados.cancela.cancelagrupo');

    Route::get('/certificados/cambiociclos', [CertificadoController::class, 'cambio_ciclo'])->name('certificados.cambio.ciclos');


    Route::get('/certificados/email/enviargrupo/{alumnos_sel}', [CertificadoController::class, 'enviargrupo'])
        ->name('certificados.email.enviargrupo');

    Route::get('/certificados/valida', [CertificadoController::class, 'validar'])->name('certificados.valida.index');
    Route::get('/certificados/valida/oficioaut/{oficio_id}', [OficioController::class, 'oficio_autenticidad'])->name('certificados.valida.oficioaut');
    Route::get('/certificados/valida/oficioapo/{oficio_id}', [OficioController::class, 'oficio_apocrifo'])->name('certificados.valida.oficioapo');
    Route::get('/certificados/valida/oficio/', [OficioController::class, 'oficio'])->name('certificados.valida.oficio');
    Route::get('/certificados/valida/agregar', [OficioController::class, 'agregar'])->name('certificados.valida.agregar');
    Route::get('/certificados/valida/editar/{oficio_id}', [OficioController::class, 'editar'])->name('certificados.valida.editar');
    Route::get('/certificados/valida/eliminar/{oficio_id}', [OficioController::class, 'eliminar'])->name('certificados.valida.eliminar');

    Route::get('/certificados/visualiza', [CertificadoController::class, 'visor'])->name('certificados.visor.index');

    //DATOS PERSONALES EFIRMA
    Route::get('/catalogos/general/datospersonales', [GeneralController::class, 'datosper_index'])->name('catalogos.general.datospersonales');
    Route::get('/catalogos/general/configura', [GeneralController::class, 'datosper_configura'])->name('catalogos.general.configura');

    //Estadisticos
    Route::get('/estadistico/documentos/calificaciones_totales', [calificaciones_ciclo::class, 'exportar_excel'])->name('estadisticos.calificaciones');

    //BOLETA
    Route::get('/adminalumnos/boleta', [BoletaController::class, 'index'])
        ->name('adminalumnos.boleta.index');

    Route::get('/adminalumnos/boleta/reporte/{alumno_id}/{ciclo_id}', [BoletaController::class, 'reporte'])
        ->name('adminalumnos.boleta.reporte');

    Route::get('/adminalumnos/boleta/reportegrupo/{grupos_sel}', [BoletaController::class, 'reportegrupo'])
        ->name('adminalumnos.boleta.reportegrupo');

  //EQUIVALENCIA
    Route::get('/adminalumnos/equivalencia', [EquivalenciaController::class, 'index'])->name('adminalumnos.equivalencia.index');
    Route::get('/adminalumnos/equivalencia/agregar', [EquivalenciaController::class, 'agregar'])->name('adminalumnos.equivalencia.agregar');
    Route::get('/adminalumnos/equivalencia/editar/{equivalencia_id}/{tipo}', [EquivalenciaController::class, 'editar'])->name('adminalumnos.equivalencia.editar');
    Route::get('/adminalumnos/equivalencia/eliminar/{equivalencia_id}/{tipo}', [EquivalenciaController::class, 'eliminar'])->name('adminalumnos.equivalencia.eliminar');
    Route::get('/adminalumnos/equivalencia/reporte/{equivalencia_id}/{tipo}', [EquivalenciaController::class, 'reporte'])->name('adminalumnos.equivalencia.reporte');

    //ESTADISTICAS
    Route::get('/estadisticas/matricula', [MatriculaController::class, 'index'])
        ->name('estadisticas.matricula.index');

    Route::get('/estadisticas/matriculac/excel/{fecha}', [MatriculaController::class, 'excel_matricula'])
    ->name('estadisticas.matricula.excel');

    Route::get('/estadisticas/grupos', [GruposController::class, 'index'])
        ->name('estadisticas.grupos.index');

    Route::get('/estadisticas/grupos/{ciclo_id}/excel', [GruposController::class, 'excel_grupos'])
        ->name('estadisticas.grupos.excel');

    Route::get('/estadisticas/calificaciones', [CalificacionesController::class, 'index'])
        ->name('estadisticas.calificaciones.index');

    Route::get('/estadisticas/calificaciones/{ciclo_id}/excel', [CalificacionesController::class, 'excel_calificaciones'])
        ->name('estadisticas.calificaciones.excel');

    Route::get('/estadisticas/tablero', [TableroController::class, 'index'])
    ->name('estadisticas.tablero.index');
    Route::get('/estadisticas/tablero/excel/{ciclo_id}/{plantel_id}/{periodo}/{grupo_id}/{turno}/{curso_id}/{docente_id}/{vars}/{chk1}/{chk2}/{chk3}/{chkr}/{chkf}/', [TableroController::class, 'excel_tablero'])
    ->name('estadisticas.tablero.excel');

    Route::get('/estadisticas/explorar', [ExplorarController::class, 'index'])
    ->name('estadisticas.explorar.index');
    Route::get('/estadisticas/explorar/excel/{ciclo_id}/{plantel_id}/{periodo}/{grupo_id}/{turno}/{curso_id}/{docente_id}/', [ExplorarController::class, 'excel_explorar'])
    ->name('estadisticas.explorar.excel');



    //CURSOS
    Route::get('/cursos/consulta', [MantenimientoController::class, 'consulta_cursos_gpo'])->name('cursos.consulta_cursos_gpo');
    Route::get('/cursos/actualizar', [MantenimientoController::class, 'actualizar_cursos'])->name('cursos.actualizar');
    Route::post('/cursos/{grupo_id}/impr_p1', [MantenimientoController::class, 'imprime_calif_grupo_p1'])->name('imprime_calif_grupo_p1'); //P1
    Route::post('/cursos/{grupo_id}/impr_p2', [MantenimientoController::class, 'imprime_calif_grupo_p2'])->name('imprime_calif_grupo_p2'); //P2
    Route::post('/cursos/{grupo_id}/impr_p3', [MantenimientoController::class, 'imprime_calif_grupo_p3'])->name('imprime_calif_grupo_p3'); //P2
    Route::post('cursos/{grupo_id}/concentrado', [MantenimientoController::class, 'imprime_concentrado_grupo'])->name('imprime_calif_concentrado');
    Route::get('/cursos/actas', [MantenimientoController::class, 'buscar_actas'])->name('cursos.actas');
    Route::get('/generar/acta_doc/{id_acta}', [MantenimientoController::class, 'generar_acta'])->name('cursos.generar_acta');

    //Ejemplo de reporte DOMPDF
    Route::get('examplereport', [ExamplereportController::class, 'examplereport'])->name('examplereport');
    //Route::get('examplereport1', [Examplereport1Controller::class, 'examplereport1'])
    //->name('examplereport1');

    Route::get('movimientos/{alumno_id}', [MovimientosController::class, 'index'])->name('movimientos');


    //Ingresos Luis Spindola
    Route::get('/adminalumnos/ingresos', [TrasladosController::class, 'ingresos'])->name('adminalumnos.ingresos.index');

    //Testing
    Route::get('testing', [TestingController::class, 'index'])->name('testing');

    //SpeedTest
    Route::get('/speedtest', [SpeedtestController::class, 'run']);

    //reconocimiento_capacitacion
    Route::get('adminalumnos/reconocimiento_capacitacion', [ReconocimientoCapacitacionController::class, 'index'])
        ->name('adminalumnos.reconocimiento_capacitacion.index');
    Route::get('adminalumnos/reconocimiento_capacitacion/descarga', [ReconocimientoCapacitacionController::class, 'descargar_diplomas'])
        ->name('adminalumnos.reconocimiento_capacitacion.descarga');
    Route::get('adminalumnos/reconocimiento_capacitacion/descarga_sf', [ReconocimientoCapacitacionController::class, 'descargar_diplomas_sf'])
        ->name('adminalumnos.reconocimiento_capacitacion.descarga_sf');

    //Reportes
    Route::get('/listas_docente', [ReportesController::class, 'concentrado'])->name('reporte.alumnos');
    Route::get('/reporte_promedio', [ReportesController::class, 'promedios'])->name('reporte.promedios');
    Route::get('/catalogo_alumnos', [ReportesController::class, 'catalogo'])->name('reporte.catalogo');
    Route::get('/reporte_plantel_fotos', [ReportesController::class, 'plantel_fotos'])->name('reporte.plantel');
    Route::get('/reporte_plantel_fotos_credenciales', [ReportesController::class, 'plantel_fotos'])->name('reporte.plantel_credenciales');
    Route::get('/reporte_egreso', [ReportesController::class, 'relacion_egreso_plantel'])->name('reporte.egresos');
    Route::get('/reporte_alumnosreprobados', [ReportesController::class, 'alumnosreprobados'])->name('reporte.alumnosreprobados');
    Route::get('/reporte_materiasreprobadas/{plantel}/{grupos}/{periodo}/{docente}/{curso}', [ReportesController::class, 'materiasreprobadas'])->name('reporte.materiasreprobadas');
    Route::get('/reporte_docentesmateriasreprobadas/{plantel}/{grupos}/{periodo}/{docente}/{curso}', [ReportesController::class, 'docentesmateriasreprobadas'])->name('reporte.docentesmateriasreprobadas');
    Route::get('/reporte_alumnosmateriasreprobadas/{plantel}/{grupos}/{periodo}/{docente}/{curso}', [ReportesController::class, 'alumnosmateriasreprobadas'])->name('reporte.alumnosmateriasreprobadas');
    Route::get('/reporte_alumnosenriesgo', [ReportesController::class, 'alumnosenriesgo'])->name('reporte.alumnosenriesgo');
    Route::get('/reporte_parmateriasreprobadas/{plantel}/{grupos}/{periodo}/{docente}/{curso}', [ReportesController::class, 'parmateriasreprobadas'])->name('reporte.parmateriasreprobadas');
    Route::get('/reporte_paralumnosmateriasreprobadas/{plantel}/{grupos}/{periodo}/{docente}/{curso}', [ReportesController::class, 'paralumnosmateriasreprobadas'])->name('reporte.paralumnosmateriasreprobadas');
    Route::get('/reporte_pardocentesmateriasreprobadas/{plantel}/{grupos}/{periodo}/{docente}/{curso}', [ReportesController::class, 'pardocentesmateriasreprobadas'])->name('reporte.pardocentesmateriasreprobadas');
    Route::get('/reporte_mejorespromedios', [ReportesController::class, 'mejorespromedios'])->name('reporte.mejorespromedios');
    Route::get('/reporte_mejorespromediosalumno/{plantel}/{periodo}', [ReportesController::class, 'mejorespromediosalumno'])->name('reporte.mejorespromediosalumno');
    Route::get('/reporte_mejorespromediosgrupo/{plantel}/{periodo}', [ReportesController::class, 'mejorespromediosgrupo'])->name('reporte.mejorespromediosgrupo');
    Route::get('/reporte_movimientosmensuales', [ReportesController::class, 'movimientosmensuales'])->name('reporte.movimientosmensuales');
    Route::get('/reporte_movmensuales/{ciclo}/{plantel}', [ReportesController::class, 'movmensuales'])->name('reporte.movmensuales');
    Route::get('/reporte_indicadordesercion', [ReportesController::class, 'indicadordesercion'])->name('reporte.indicadordesercion');
    Route::get('/reporte_indicadesercion/{ciclo}', [ReportesController::class, 'indicadesercion'])->name('reporte.indicadesercion');
    Route::get('/reporte_egresados', [ReportesController::class, 'egresados'])->name('reporte.egresados');
    Route::get('/reporte_egresadosciclo/{ciclo}', [ReportesController::class, 'egresadosciclo'])->name('reporte.egresadosciclo');
    Route::get('/reporte_ingresos', [ReportesController::class, 'ingresos'])->name('reporte.ingresos');
    Route::get('/reporte_ingresosciclo/{ciclo}', [ReportesController::class, 'ingresosciclo'])->name('reporte.ingresosciclo');
    Route::get('/documentos_expediente', [ReportesController::class, 'documento_expediente'])->name('reporte.documento_expediente');
    Route::get('reporte_asignaturas_reprobadas', [ReportesController::class, 'asignaturas_reprobadas'])->name('reporte.asignaturas_reprobadas');


    //Reporte Nuevo ingreso
    Route::get('/alumnos_nuevos_grupo', [ReportesController::class, 'nuevos_alumnos'])->name('reporte.nuevos_alumnos');

    Route::get('/explorador', [ExploradorController::class, 'index'])->name('file-explorer');
    Route::get('/file-explorer/folder', [ExploradorController::class, 'folder'])->name('file-explorer.folder');
    Route::get('/file-explorer/download', [ExploradorController::class, 'download'])->name('file-explorer.download');

    Route::get('/auxiliar_scripts', [AuxscriptsController::class, 'asignar_rol'])->name('asignar_rol');

    Route::get('/certificados/sibal', [CertificadoController::class, 'sibal'])->name('sibal');
    Route::get('/certificados/sibal/{alumno_id}', [CertificadoController::class, 'certificado_sibal'])->name('sibal.certificado');

});
//Para ver archivos
Route::get('/archivo/{tipo_archivo}/{alumno_id}', [ArchivosController::class, 'mostrarArchivo'])->name('archivo.mostrar');

Route::get('/archivo/tipo/{tipo_archivo}/{alumno_id}', [ArchivosController::class, 'determina_tipo'])->name('archivo.tipo');

Route::get('/archivo_rev/{tipo_archivo}/{alumno_id}', 
    [ArchivosController::class, 'obtenerArchivo']
)->name('archivo.obtener');

Route::get('/archivo/descargar/{tipo_archivo}/{alumno_id}', [ArchivosController::class, 'descargarArchivo'])
    ->name('archivo.descargar');

//CERTIFICADO VERIFICACIÓN NO REQUIERE INICIAR SESION
//Route::get('/certificados/verificar', [CertificadoController::class, 'verificar']);
Route::get('/certificados/verificar//{folio}', [CertificadoController::class, 'verificar_folio_sin_curp']);

Route::get('/certificados/verificar/{curp}/{folio}', [CertificadoController::class, 'verificarfolio']);



Route::get('/validacredencial/{apellidos}/{noexpediente}', [constanciasController::class, 'validacredencial'])->name('validacredencial');
Route::get('/validaconstancia/{parametros}', [constanciasController::class, 'validaconstancia'])->name('validaconstancia');


require __DIR__ . '/auth.php';
