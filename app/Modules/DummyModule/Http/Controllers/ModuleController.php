<?php

namespace App\Modules\DummyModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
	public function home(){
		$frontendUrl = route('dummy-module.frontend');
		$backenddUrl = route('dummy-module.backend');
		return "hello from ModuleController! Please visit <a href='$frontendUrl'>$frontendUrl</a> or <a href='$backenddUrl'>$backenddUrl</a>";
	}

	public function frontend(){
		 return view ('dummy-module::frontend');
	}

	public function backend(){
		 return view ('dummy-module::backend');
	}
}
