<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Vfile;

class AdminController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

    public function index() {
		
        return view('admin/dashboard');
		
    }
	
	public function delete($type, $id, Request $request) {
		
		if ($type == 'post') {
			$rec = Post::find($id);
		}
		elseif ($type == 'vfile') {
			$rec = Vfile::find($id);
		}
	
		if (!$rec) {
			return redirect()->back()->with('error', 'Не удалось удалить!');
		}
		
		$rec->delete();
		
		return redirect()->back()->with('success', 'Удалено');
	
	}
	
}
