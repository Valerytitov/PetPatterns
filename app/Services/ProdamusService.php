<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class ProdamusService
{
    /**
     * Генерирует ссылку на оплату через Prodamus Payform
     */
    public function generatePaymentUrl(\App\Models\Order $order)
    {
        $baseUrl = 'https://beriisheu.payform.ru/';
        $params = [
            'email'    => $order->email,
            'order_id' => $order->id,
            'name'     => 'Выкройка для питомца',
            'sum'      => $order->sum,
        ];
        return $baseUrl . '?' . http_build_query($params);
    }

    /**
     * Обрабатывает webhook от Prodamus
     */
    public function handleWebhook(Request $request)
    {
        // TODO: проверить подпись, найти заказ, отметить как оплаченный
        // $data = $request->all();
        // ...
    }
} 