<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\HoraPlantelModel;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class HoraPlantelController extends Controller
{
    public function horas_del_plantel($plantel_id)
    {
        Paginator::useBootstrap();
        $plantel = PlantelesModel::find($plantel_id);
        $horas = HoraPlantelModel::where('plantel_id',$plantel->id)->paginate(10);
        return view('catalogos.horas.index', compact('plantel', 'horas'));
    }
}
