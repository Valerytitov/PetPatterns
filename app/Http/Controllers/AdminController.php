<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Vfile;
use App\Models\Order;
use App\Models\Review;

class AdminController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

    public function index() {
		
		// Статистика
		$stats = [
			'orders_total' => Order::count(),
			'orders_paid' => Order::where('status', 'paid')->count(),
			'orders_pending' => Order::where('status', 'pending')->count(),
			'vfiles_total' => Vfile::count(),
			'posts_total' => Post::count(),
			'reviews_total' => Review::count(),
		];
		
		// Последние заказы
		$recent_orders = Order::with('vfile')
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();
		
		// Последние выкройки
		$recent_vfiles = Vfile::orderBy('created_at', 'desc')
			->limit(5)
			->get();
		
		// Последние посты
		$recent_posts = Post::orderBy('created_at', 'desc')
			->limit(5)
			->get();
		
        return view('admin/dashboard', compact('stats', 'recent_orders', 'recent_vfiles', 'recent_posts'));
		
    }
	
	public function delete($type, $id, Request $request) {
		
		if ($type == 'post') {
			$rec = Post::find($id);
		}
		elseif ($type == 'vfile') {
			$rec = Vfile::find($id);
		}
		elseif ($type == 'review') {
			$rec = Review::find($id);
		}
		elseif ($type == 'order') {
			$rec = Order::find($id);
		}
	
		if (!$rec) {
			return redirect()->back()->with('error', 'Не удалось удалить!');
		}
		
		$rec->delete();
		
		return redirect()->back()->with('success', 'Удалено');
	
	}
	
}
