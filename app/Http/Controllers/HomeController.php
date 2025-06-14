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
		
		$vfile = Vfile::first(); // Получаем первую выкройку для демонстрации
		if (!$vfile) {
			return back()->withErrors(['error' => 'Выкройки для конструктора не найдены.']);
		}

		$props = \App\Helpers\VFile::buildProps($vfile->vit_data);
		$props = array_chunk($props, 3);

		$return = compact('vfile', 'props');
		return view('front/constructor/index', $return);
		
	}
	
	public function constructor_use($id, Request $request) {
		
		shell_exec('/usr/bin/python3 /var/www/html/pscript/test.py');

		$vfile = Vfile::find($id);
		if (!$vfile) {
			abort(404);
		}
		
		$props = \App\Helpers\VFile::buildProps($vfile->vit_data);
		$props = array_chunk($props, 3);
		
		$return = compact('vfile', 'props');
		
		return view('front/constructor/vfile', $return);
		
	}
	
}
