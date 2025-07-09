<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\PerfilModel;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function perfil()
    {
        $user = Auth()->user();
        $perfil = PerfilModel::where('user_id',$user->id)->first();
        return view('administracion.perfil.perfil', compact('user','perfil'));
    }

    public function cons_perfil($user_id)
    {
        if(Auth()->user()->hasRole('S-Administrador'))
        {
            $perfil = PerfilModel::where('user_id',$user_id)->first();

            if($perfil)
            {
                $user = $perfil->user;
                return view('perfiles.perfiles_cons', compact('user','perfil'));
            }   
        }
    }
}
