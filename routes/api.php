<?php

use App\Http\Controllers\Api\Appmobile\DataApiController;
use App\Http\Controllers\Api\Adminalumnos\AlumnoApiController;
use App\Http\Controllers\Api\Catalogos\AsignaturaApiController;
use App\Http\Controllers\Api\Catalogos\CicloEscApiController;
use App\Http\Controllers\Api\Catalogos\GeneralApiController;
use App\Http\Controllers\Api\Catalogos\PlandeEstudioApiController;
use App\Http\Controllers\Api\Catalogos\PlantelApiController;
use App\Http\Controllers\Api\Catalogos\UbicacionGeoApiController;
use App\Http\Controllers\Api\Escolares\GeneralesApiController;
use App\Http\Controllers\Catalogos\CiclosConfigController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/logins', function (Request $request) {
    //recibe request name, email y token_name, busca o crea al usuario y le genera el token
    $user_remoto = User::where('email', $request->email)->first();
    if($user_remoto == null)
    {
        $user_remoto = User::create([
            'name'              =>  $request->name,
            'email'             =>  $request->email,
            'email_verified_at' =>  date("Y-m-d H:i:s"),
            'password'          =>  Hash::make('LTODO2023'),
        ]);
    }

    return response()->json([
        'user' => [
            'name'  => $user_remoto->name,
            'email' => $user_remoto->email,
        ],
        'token' => $user_remoto->createToken($request->token_name),
    ]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    //Apis con token:
    Route::post('/alumnos/busqueda_correo', [AlumnoApiController::class, 'buscar_alumno']);

});
Route::prefix('api/ciclos_config')->group(function () {
    Route::post('/', [CiclosConfigController::class, 'store']);
    Route::get('/{id}', [CiclosConfigController::class, 'show']);
    Route::put('/{id}', [CiclosConfigController::class, 'update']);
});

Route::post('ciclo_esc/store', [CicloEscApiController::class, 'storeCicloEsc']);
Route::get('ciclo_esc/get/{id}', [CicloEscApiController::class, 'getCicloEsc']);
Route::post('ciclo_esc/{id}/editar', [CicloEscApiController::class, 'editarCicloEsc']);
//ANA MOLINA 28/08/2023
Route::get('ciclo_esc/getciclos', [CicloEscApiController::class, 'getCiclosEsc']);
//ANA MOLINA 28/08/2023
Route::get('plan_estudio/getplanes/{id_plantel}', [PlandeEstudioApiController::class, 'getPlanesEst']);

Route::post('alumno/store', [AlumnoApiController::class, 'storeAlumno']);
Route::get('alumno/get/{id}', [AlumnoApiController::class, 'getAlumno']);
Route::post('alumno/{id}/editar', [AlumnoApiController::class, 'editarAlumno']);
Route::get('alumno/getmaxexpediente/{id_plantel}/{id_ciclo}', [AlumnoApiController::class, 'getMaxExpediente']);


Route::get('appmobile/test', [DataApiController::class, 'getTest']);

Route::get('appmobile/testparameter/{parameter}', [DataApiController::class, 'getTestParameter']);

Route::get('appmobile/getalumno/{noexpediente}', [DataApiController::class, 'getAlumno']);

Route::get('appmobile/getgrupo/{alumno_id}', [DataApiController::class, 'getGrupo']);
Route::get('appmobile/getimagen/{alumno_id}', [DataApiController::class, 'getImagen']);

Route::get('appmobile/getcalificaciones/{alumno_id}/{parcial}', [DataApiController::class, 'getCalificaciones']);


Route::get('appmobile/validaemail/{email}', [DataApiController::class, 'validaemail']);

//ANA MOLINA 29/08/2023
Route::get('ubicacion_geo/getnacionalidades', [UbicacionGeoApiController::class, 'getNacionalidades']);
 //ANA MOLINA 29/08/2023
Route::get('ubicacion_geo/getpaises', [UbicacionGeoApiController::class, 'getPaises']);

//ANA MOLINA 29/08/2023
Route::get('ubicacion_geo/getestados/{id_pais}', [UbicacionGeoApiController::class, 'getEstados']);

//ANA MOLINA 29/08/2023
Route::get('ubicacion_geo/getmunicipios/{id_estado}', [UbicacionGeoApiController::class, 'getMunicipios']);

//ANA MOLINA 29/08/2023
Route::get('ubicacion_geo/getlocalidades/{id_municipio}', [UbicacionGeoApiController::class, 'getLocalidades']);

//ANA MOLINA 29/08/2023
Route::get('ubicacion_geo/getlugares', [UbicacionGeoApiController::class, 'getLugares']);

//ANA MOLINA 01/09/2023
Route::get('escolares/gettipoperiodos', [GeneralesApiController::class, 'getTipoperiodos']);

//ANA MOLINA 01/09/2023
Route::get('escolares/getperiodos/{id_tipoperiodo}', [GeneralesApiController::class, 'getPeriodos']);

//ANA MOLINA 01/09/2023
Route::get('escolares/getsecundarias', [GeneralesApiController::class, 'getSecundarias']);


//ANA MOLINA 01/09/2023
Route::get('escolares/getbecas', [GeneralesApiController::class, 'getBecas']);


//ANA MOLINA 01/09/2023
Route::get('escolares/getestatus', [GeneralesApiController::class, 'getEstatus']);

//ANA MOLINA 01/09/2023
Route::get('catalogos/getdiscapacidades', [GeneralApiController::class, 'getDiscapacidades']);

//ANA MOLINA 01/09/2023
Route::get('catalogos/getetnias', [GeneralApiController::class, 'getEtnias']);

//ANA MOLINA 01/09/2023
Route::get('catalogos/getserviciosmedicos', [GeneralApiController::class, 'getServiciosmedicos']);


//ANA MOLINA 05/09/2023
Route::get('catalogos/getplantel/{id_plantel}', [PlantelApiController::class, 'getPlantel']);

route::get('alumno/buscar', [AlumnoApiController::class, 'buscar']);

route::get('ct/buscar', [AlumnoApiController::class, 'buscar_ct']);
Route::get('municipios/{estado}', [AlumnoApiController::class, 'municipios']);
Route::get('localidades/{estado}/{municipio}', [AlumnoApiController::class, 'localidades']);

Route::middleware('auth', 'bitacora')->group(function(){
//BUSCADOR SUPERIOR


//Cambio de clave asignatura Kardex
route::get('asignatura/buscar', [AsignaturaApiController::class, 'buscar_asignatura']);
Route::get('cambio_clave/{clave_asignatura}', [AsignaturaApiController::class, 'cambioClave'])->name('cambioclave.kardex');






});

