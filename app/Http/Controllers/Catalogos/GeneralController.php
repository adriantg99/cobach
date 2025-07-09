<?php
// ANA MOLINA 11/05/2024
namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function datosper_index()
    {
        return view('catalogos.general.datospersonales');
    }


    public function datosper_configura()
    {
        return view('catalogos.general.configura');
    }

}
