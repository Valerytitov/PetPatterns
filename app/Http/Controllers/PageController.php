<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vfile; // <-- 1. Добавляем правильную модель Vfile

class PageController extends Controller
{
    public function index()
    {
        // 2. Используем модель Vfile и переменную $products, как ожидает шаблон
        $products = Vfile::orderBy('id', 'desc')->get();

        $return = compact('products');

        // 3. Исправляем путь к шаблону на стандартный для Laravel
        return view('front.shop.list', $return);
    }
}
