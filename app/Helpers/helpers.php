<?php

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Support\Facades\Auth;



if (!function_exists('obtenerPlanteles')) {
    function obtenerPlanteles()
    {
        $user = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        $todos_los_valores = 0;
        $validaciones = [];
        $planteles_array = array(
            'VSE',
            'NAV',
            'EFK',
            'AFU',
            'CAB',
            'PPE',
            'REF',
            'ELR',
            'SLR',
            'OB2',
            'ALA',
            'EMP',
            'ETC',
            'SON',
            'AOS',
            'SIR',
            'PYA',
            'NHE',
            'NOG',
            'QUE',
            'FFS',
            'PEC',
            'JMM',
            'HE5',
            'OB3',
            'NAC',
            'BDK',
            'CAL',
            'HE7',
            'NO2'
        );
        foreach ($roles as $role) {


            if ($role === "control_escolar"|| $role === "super_admin" || $role === "autorizar_rev" || $role === "carga_planteles_todos") {
                $todos_los_valores = 1;
                break;
            } elseif ((strpos($role, "control_escolar_") === 0)) {
                $validaciones[] = substr($role, 16);
                $todos_los_valores = 2;
                continue;
            } elseif (in_array($role, $planteles_array)) {
                $validaciones[] = $role;
                $todos_los_valores = 2;
                continue;
            }


        }

        if ($todos_los_valores === 1) {
            return PlantelesModel::orderBy('id')->get();
        } elseif ($todos_los_valores === 2) {
            return PlantelesModel::whereIn('abreviatura', $validaciones) ->get();
        }

        return collect();
    }
}

if(!function_exists('permisos')){
    function permisos(){
        $user = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        $todos_los_valores = 0;
      
        foreach ($roles as $role) {


            if ($role === "control_escolar"|| $role === "super_admin") {
                $todos_los_valores = 1;
                break;
            } elseif ((strpos($role, "control_escolar_") === 0)) {
                $todos_los_valores = 2;
                break;
            }
            else{
                $todos_los_valores = 3;
            }


        }


        return $todos_los_valores;
    }
}

