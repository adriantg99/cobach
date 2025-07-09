<?php
// ANA MOLINA 04/09/2023
namespace App\Http\Controllers\Api\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalogos\GeneralResource;
use App\Models\Catalogos\DiscapacidadModel;
use App\Models\Catalogos\EtniaModel;
use App\Models\Catalogos\ServiciomedicoModel;
use AIlluminate\Http\Request;

class GeneralApiController extends Controller
{
    //
    public function getDiscapacidades()
    {
        $discapacidades = DiscapacidadModel::select('id','nombre')->orderBy('nombre')->get();
       return new GeneralResource($discapacidades);
    }

    public function getEtnias()
    {
        $etnias = EtniaModel::select('id','nombre')->orderBy('nombre')->get();
       return new GeneralResource($etnias);
    }
    public function getServiciosmedicos()
    {
        $servicios = ServiciomedicoModel::select('id','nombre')->orderBy('nombre')->get();
       return new GeneralResource($servicios);
    }

}
