<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

use App\Models\Order;

class AdminOrderController extends Controller {
	
    public function index(Request $request) {
        $query = \App\Models\Order::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        $list = $query->orderBy('id', 'desc')->get();
        $return = compact('list');
        return view('admin/orders/index', $return);
    }
	
	public function form($id, Request $request) {
		
		$title = 'Добавить ';
		$rec = new \App\Models\Order;
		
		if ($id) {
			
			$rec = \App\Models\Order::find($id);
			if (!$rec) {
				return redirect()->route('admin.orders')->with('error', 'Заказ не найден');
			}
			
			$title = 'Редактировать ';
			
		}
		
		$title .= 'заказ';
		
		if ($request->isMethod('post')) {
			
			$rules = [
				'status' => 'required',
				'email' => 'required|email',
			];
			
			$msgs = [
			 
				'status.required' => 'Пожалуйста, укажите статус заказа',
				'email.required' => 'Пожалуйста, укажите email',
				'email.email' => 'Некорректный email',
			
			];
			
			$validator = Validator::make($request->all(), $rules, $msgs);			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$rec->fill($request->only(['status', 'email']));
			$rec->save();

			return redirect()->route('admin.orders')->with('success', 'Сохранено!');
			 
		}
		
		$return = compact('id', 'rec', 'title');

		return view('admin/orders/form', $return);
		
	}

    public function deleteMass(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Не выбраны заказы для удаления');
        }
        \App\Models\Order::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Выбранные заказы удалены!');
    }

}
