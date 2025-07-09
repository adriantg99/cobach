<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Catalogos\NucleoModel;
use Illuminate\Http\Request;
use App\Traits\BitacoraTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NucleoController extends Controller {

    /* Traits */
    use BitacoraTrait;

    /**
     * Método para mostrar la vista de índice de los catálogos de núcleos.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View La vista de índice de los catálogos de núcleos.
     */
    public function index(): View {
        return view( 'catalogos.nucleos.index' );
    }

}
