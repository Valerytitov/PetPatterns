<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class BlogController extends Controller {

    public function index() {
		
		$posts = Post::orderBy('id', 'desc')->get();
		
		$return = compact('posts');
		
        return view('front/blog/list', $return);
		
    }
	
	public function single($slug, Request $request) {
		
		$post = Post::where(['slug' => $slug])->first();
		if (!$post) {
			abort(404);
		}
		
		$posts = Post::orderBy('id', 'desc')->get();
		
		$return = compact('post', 'posts');
		
		return view('front/blog/single', $return);
		
	}
	
}
