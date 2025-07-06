<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

use App\Models\Review;

class AdminReviewController extends Controller {
	
    public function index(Request $request) {
		
		$list = Review::orderBy('id', 'desc')->get();
		
		$return = compact('list');

        return view('admin/reviews/index', $return);

    }
	
	public function form(Request $request, $id = 0) {
		
		$title = 'Добавить ';
		$rec = new Review;
		
		if ($id) {
			
			$rec = Review::find($id);
			if (!$rec) {
				return redirect()->route('admin.reviews')->with('error', 'Запись не найдена');
			}
			
			$title = 'Редактировать ';
			
		}
		
		$title .= 'отзыв';
		
		if ($request->isMethod('post')) {
			
			$rules = [
			
				'title' => 'required',
				'author' => 'required',
				'content1' => 'required',
			
			];
			
			$msgs = [
			 
				'title.required' => 'Пожалуйста, укажите заголовок отзыва',
				'author.required' => 'Пожалуйста, укажите автора отзыва',
				'content1.required' => 'Пожалуйста, введите содержимое отзыва',
			
			];
			
			if (!$id) {

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
				$image = '/rf/'.$request->file('image')->storeAs('', $image_file, ['disk' => 'rf']);
				
				
			}
			
			$data = [
			
				'title' => $request->title,
				'author' => $request->author,
				'content' => $request->content1,
				'image' => $image,
			
			];
			
			$rec->fill($data);
			$rec->save();

			return redirect()->route('admin.reviews')->with('success', 'Сохранено!');
			 
		}
		
		$return = compact('id', 'rec', 'title');

		return view('admin/reviews/form', $return);
		
	}

}
