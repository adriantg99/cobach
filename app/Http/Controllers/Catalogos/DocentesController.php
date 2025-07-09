<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;

class DocentesController extends Controller
{
    //

    public function index()
    {
        $planteles = PlantelesModel::get();


        return view('catalogos.docentes.index', compact('planteles'));
    }

    public function index_post(Request $request)
    {
        $plantel = PlantelesModel::find($request->plantel_id);
        

        return view('catalogos.docentes.index_plantel', compact('plantel'));
    }
}
