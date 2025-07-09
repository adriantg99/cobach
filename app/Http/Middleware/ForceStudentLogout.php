<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ForceStudentLogout {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        if (Auth::check()) {
            $user = Auth::user();
            $student = DB::table('alu_alumno')->where('correo_institucional', $user->email)->first();
            /*if ($student) {
                Log::info('Forzando cierre de sesión para el usuario: ' . $user->email);
                Auth::logout();
                return redirect('/login')->with('error', 'Plataforma en mantenimiento');
            }*/
        }
        return $next($request);
    }
}
