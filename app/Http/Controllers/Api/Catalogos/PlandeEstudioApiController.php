<?php

// ANA MOLINA 22/08/2023
namespace App\Http\Controllers\Api\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalogos\PlandeEstudioResource;
use App\Models\Catalogos\PlandeEstudioModel;
use Illuminate\Http\Request;

class PlandeEstudioApiController extends Controller
{
    //
    public function getPlanesEst($id_plantel)
    {
        $planes = PlandeEstudioModel::where('id_plantel',$id_plantel)->select('id','nombre')->orderBy('nombre', 'desc')->get();
        //dd($planes);
        return new PlandeEstudioResource($planes);
    }

}
