<?php
// ANA MOLINA 07/08/2023
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamplereportController extends Controller
{
    public function exreporte()
    {
        return view('examplereport');
    }
}
