<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class PageController extends Controller {

    public function index() {
		
		$posts = Post::orderBy('id', 'desc')->get();
		
		$return = compact('posts');
		
        return view('front/shop/list', $return);
		
    }
	
}
