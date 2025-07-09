<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\BitacoraTrait;

class TestingController extends Controller {
    
    use BitacoraTrait;

    public function index() {
        $this->createEntry(__CLASS__, __NAMESPACE__, __METHOD__, "Testing bitacora desde Controller");
        return view('testing.testing-index');
        }
}
