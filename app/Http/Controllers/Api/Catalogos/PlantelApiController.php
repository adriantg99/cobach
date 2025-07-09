<?php

// ANA MOLINA 22/08/2023
namespace App\Http\Controllers\Api\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalogos\GeneralResource;
use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;

class PlantelApiController extends Controller
{
    //
    public function getPlantel($id_plantel)
    {
        $plantel = PlantelesModel::where('id',$id_plantel)->get();
        //dd($planes);
        return new GeneralResource($plantel);
    }

}
