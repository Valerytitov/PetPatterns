<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vfile;

class HomeController extends Controller {

    public function index() {
		
		// die('Updating data...');
        return view('front/home');
		
    }
	
	public function constructor(Request $request) {
		
		return view('front/constructor/index');
		
	}
	
	public function constructor_use($id, Request $request) {
		
		shell_exec('/usr/bin/python3 /var/www/html/pscript/test.py');

		$vfile = Vfile::find($id);
		if (!$vfile) {
			abort(404);
		}
		
		$props = \App\Helpers\VFile::buildProps($vfile->vit_data);
		$props = array_chunk($props, 3);
		$props = json_encode($props, JSON_UNESCAPED_UNICODE);
		$props = json_decode($props);
		
		$return = compact('vfile', 'props');
		
		return view('front/constructor/vfile', $return);
		
	}
	
}
