<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vfile;
use App\Models\Prop;

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

        $defaultValues = [
            '@ДС' => '29',
            '@ДИ' => '@ДС',
            '@ОГ' => '42',
            '@ОТ' => '40',
            '@ОШ' => '28',
            '@Мпл' => '6',
            '@Дпл' => '9',
            '@Дзл' => '11',
        ];

		// Получаем параметры напрямую из таблицы props
		$props = Prop::orderBy('sort_order')->get()->map(function ($prop) use ($defaultValues) {
            $propArray = $prop->toArray();
            if (isset($defaultValues[$propArray['prop_key']])) {
                $propArray['default'] = $defaultValues[$propArray['prop_key']];
            }
            return $propArray;
        })->toArray();

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

        $defaultValues = [
            '@ДС' => '29',
            '@ДИ' => '@ДС',
            '@ОГ' => '42',
            '@ОТ' => '40',
            '@ОШ' => '28',
            '@Мпл' => '6',
            '@Дпл' => '9',
            '@Дзл' => '11',
        ];
		
		// Получаем параметры напрямую из таблицы props
		$props = Prop::orderBy('sort_order', 'asc')->get()->map(function ($prop) use ($defaultValues) {
            $propArray = $prop->toArray();
            if (isset($defaultValues[$propArray['prop_key']])) {
                $propArray['default'] = $defaultValues[$propArray['prop_key']];
            }
            return $propArray;
        })->toArray();
		
		$props = array_chunk($props, 3);
		
		$return = compact('vfile', 'props');
		
		return view('front/constructor/vfile', $return);
		
	}
	
}
