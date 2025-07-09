<?php
// ANA MOLINA 04/09/2023
namespace App\Http\Controllers\Api\Escolares;

use App\Http\Controllers\Controller;
use App\Http\Resources\Escolares\GeneralesResource;
use App\Models\Escolares\TipoperiodoModel;
use App\Models\Escolares\PeriodoModel;
use App\Models\Escolares\SecundariaModel;
use App\Models\Escolares\EstatusModel;
use App\Models\Escolares\BecaModel;
use Illuminate\Http\Request;

class GeneralesApiController extends Controller
{
    //


    public function getTipoperiodos()
    {
        $tipos = TipoperiodoModel::select('id','nombre')->orderBy('nombre')->get();
         return new GeneralesResource($tipos);
    }
    public function getPeriodos($id_tipoperiodo)
    {
        $periodos = PeriodoModel::where('id_tipoperiodo',$id_tipoperiodo)->select('id','nombre')->orderBy('nombre')->get();
        return new GeneralesResource($periodos);
    }

    public function getSecundarias()
    {
        $secundarias = SecundariaModel::select('id','nombre')->orderBy('nombre')->get();
       return new GeneralesResource($secundarias);
    }
    public function getBecas()
    {
        $becas = BecaModel::select('id','nombre')->orderBy('nombre')->get();
       return new GeneralesResource($becas);
    }
    public function getEstatus()
    {
        $estatus = EstatusModel::select('id','nombre')->orderBy('nombre')->get();
       return new GeneralesResource($estatus);
    }
}
