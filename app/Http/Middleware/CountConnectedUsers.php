<?php

namespace App\Http\Middleware;

use App\Models\Administracion\BitacoraModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CountConnectedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        ini_set('memory_limit', '128M');
        ini_set('max_execution_time', '60'); // 1 minuto
        $status = DB::select("show status where `variable_name` = 'Threads_connected';");
        //dd($status[0]->Value);
        if($status[0]->Value >= 1200)
        {
            //dd($conteo_usuarios_conectados);
            //VERIFICA QUE EL NUMERO DE CONECCIONES ESTE POR DEBADO DE 800

            $fecha_hace_5_minutos = Carbon::now()->subMinutes(5);
            $user = Auth::user();

            //$usuario_id = DB::table('users')
            //    ->select('id')
            //    ->where('email', $user->email)
            //    ->first();

            $usuario_registrado = BitacoraModel::where('user_id', $user->id)
            ->where('controller', '!=', 'CountConnectedUsers')
            ->where('created_at', '>=', $fecha_hace_5_minutos)
            ->orderBy('created_at')
            ->first();

            //SI HAY ACTIVIDAD DEL USUARIO EN LOS ULTIMOS 5MIN QUE NO SEA DEL MIDLEW
            if($usuario_registrado->created_at <= $fecha_hace_5_minutos){

            }
            else{
                $conteo_usuarios_conectados = BitacoraModel::select('user_id')
                    ->where('created_at', '>=', $fecha_hace_5_minutos)
                    ->distinct('user_id')
                    ->count();

                //dd($conteo_usuarios_conectados);
                //Limite anterior 120
                if ($conteo_usuarios_conectados > 300) {

                    BitacoraModel::create([
                    'user_id'   =>  Auth()->user()->id,
                    'ip'        =>  (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                    'path'      =>  $_SERVER["REQUEST_URI"],
                    'method'    =>  $_SERVER['REQUEST_METHOD'],
                    //MODIFICAR MANUALMENTE LOS SIGUIENTES CAMPOS
                    'controller'    =>  'CountConnectedUsers',
                    //'component'     =>  'FormComponent',
                    'function'  =>  'hadle',
                    'description'   =>  'Se denegó el acceso por sobrecarga de alumnos en el servidor usuario:'.Auth()->user()->id.' - Limite de alumnos simultaneos 120 - conteo: '.$conteo_usuarios_conectados,
                ]);


                    Auth::logout();
                    //echo "<script>alert('Máximo de alumnos conectados alcanzado. Intentelo más tarde'); window.location.href = '/';</script>";
                    //exit; // Asegúrate de que el script se detiene después de la redirección
                    return redirect('/login')->with('error', 'Máximo de alumnos conectados alcanzado. Intentelo más tarde');
                }
            }
            // Verificar si el usuario ya está en la lista de usuarios conectados

        }

       
        return $next($request);
    }


}

