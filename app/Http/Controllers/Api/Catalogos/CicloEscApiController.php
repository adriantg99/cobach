<?php

namespace App\Http\Controllers\Api\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\CicloEscRequest;
use App\Http\Resources\Catalogos\CicloEscResource;
use App\Models\Catalogos\CicloEscModel;
use Illuminate\Http\Request;

class CicloEscApiController extends Controller
{
    //
    public function storeCicloEsc (CicloEscRequest $request)
    {
        $ciclo_esc = CicloEscModel::create($request->validated());
        return new CicloEscResource($ciclo_esc);
    }

    public function getCicloEsc($id)
    {
        $ciclo_esc = CicloEscModel::find($id);
        return new CicloEscResource($ciclo_esc);
    }

    public function editarCicloEsc(CicloEscRequest $request, $id)
    {
        $ciclo_esc = CicloEscModel::find($id);
        $ciclo_esc->update($request->validated());
        return new CicloEscResource($ciclo_esc);
    }

    //ANA MOLINA 28/08/2023
    public function getCiclosEsc()
    {
        $ciclos_esc = CicloEscModel::select('id','nombre')->orderBy('nombre', 'desc')->get();
        //dd($ciclos_esc);
        return new CicloEscResource($ciclos_esc);
    }

}
