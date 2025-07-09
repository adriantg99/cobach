<?php

// ANA MOLINA 22/08/2023
namespace App\Http\Controllers\Api\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Catalogos\UbicacionGeoResource;
use App\Models\Catalogos\NacionalidadModel;
use App\Models\Catalogos\PaisModel;
use App\Models\Catalogos\EstadoModel;
use App\Models\Catalogos\MunicipioModel;
use App\Models\Catalogos\LocalidadModel;
use App\Models\Catalogos\LugarModel;
use Illuminate\Http\Request;

class UbicacionGeoApiController extends Controller
{
    //
    public function getNacionalidades()
    {
        $nacionalidades = NacionalidadModel::select('id','nombre')->orderBy('nombre')->get();
       return new UbicacionGeoResource($nacionalidades);
    }

    public function getPaises()
    {
        $paises = PaisModel::select('id','nombre')->orderBy('nombre')->get();
         return new UbicacionGeoResource($paises);
    }
    public function getEstados($id_pais)
    {
        $estados = EstadoModel::where('id_pais',$id_pais)->select('id','nombre')->orderBy('nombre')->get();
         return new UbicacionGeoResource($estados);
    }
    public function getMunicipios($id_estado)
    {
        $municipios = MunicipioModel::where('id_estado',$id_estado)->select('id','nombre')->orderBy('nombre')->get();
         return new UbicacionGeoResource($municipios);
    }
    public function getLocalidades($id_municipio)
    {
        $localidades = LocalidadModel::where('id_municipio',$id_municipio)->select('id','nombre')->orderBy('nombre')->get();
         return new UbicacionGeoResource($localidades);
    }
    public function getLugares()
    {
        $lugares = LugarModel::select('id','nombre')->orderBy('nombre')->get();
         return new UbicacionGeoResource($lugares);
    }
}
