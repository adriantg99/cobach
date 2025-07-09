<?php

namespace App\Http\Middleware;

use App\Models\Administracion\BitacoraModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class Bitacora
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //$ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) )? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];



        $data = [
            'user_id'           =>  Auth()->user()->id,
            'path'              =>  $request->path(),
            'method'            =>  $request->method(),
            'ip'                =>  $request->ip(),
            'function'          =>  'handle',
            'description'       =>  'Middleware'
        ];
        BitacoraModel::create($data);
        return $next($request);
    }
}
