<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

use App\Models\Post;

class AdminPostController extends Controller {
	
    public function index(Request $request) {
		
		$list = Post::orderBy('id', 'desc')->get();
		
		$return = compact('list');

        return view('admin/posts/index', $return);

    }
	
	public function form(Request $request, $id = 0) {
		
		$title = 'Добавить ';
		$rec = new Post;
		
		if ($id) {
			
			$rec = Post::find($id);
			if (!$rec) {
				return redirect()->route('admin.posts')->with('error', 'Запись не найдена');
			}
			
			$title = 'Редактировать ';
			
		}
		
		$title .= 'запись';
		
		if ($request->isMethod('post')) {
			
			$rules = [
			
				'slug' => 'required',
				'title' => 'required',
				'short' => 'required',
				'content' => 'required',
			
			];
			
			$msgs = [
			 
				'slug.required' => 'Пожалуйста, укажите URL записи',
				'slug.unique' => 'Запись с таким URL уже существует, выберите другое!',
				'title.required' => 'Пожалуйста, укажите заголовок записи',
				'short.required' => 'Пожалуйста, укажите краткое содержание записи',
				'content.required' => 'Пожалуйста, введите содержимое записи',
			
			];
			
			if (!$id) {
				$rules['slug'] .= '|unique:posts';
			}
			
			$validator = Validator::make($request->all(), $rules, $msgs);			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$image = $rec->image;
			
			/* Изображение */
			if ($request->file('image')) {
				
				$image_ext = $request->image->getClientOriginalExtension();
				$image_file = time().'.'.$image_ext;
				$image = '/bf/'.$request->file('image')->storeAs('', $image_file, ['disk' => 'bf']);
				
				
			}
			
			$data = [
			
				'slug' => $request->slug,
				'title' => $request->title,
				'short' => $request->short,
				'content' => $request->content,
				'image' => $image,
			
			];
			
			$rec->fill($data);
			$rec->save();

			return redirect()->route('admin.posts')->with('success', 'Сохранено!');
			 
		}
		
		$return = compact('id', 'rec', 'title');

		return view('admin/posts/form', $return);
		
	}

}
