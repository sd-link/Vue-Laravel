<?php

namespace App\Http\Controllers\Backend\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackendController extends Controller
{
    
    public function dashboard()
    {
        return view ('core.dashboard.index');
    }
}
