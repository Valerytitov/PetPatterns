<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdamusService;
use App\Models\Order;

class ProdamusController extends Controller
{
    protected $prodamusService;

    public function __construct(ProdamusService $prodamusService)
    {
        $this->prodamusService = $prodamusService;
    }

    /**
     * Редиректит пользователя на платёжную страницу Prodamus
     */
    public function pay(Request $request, $patternId)
    {
        // Получаем выкройку
        $vfile = \App\Models\Vfile::find($patternId);
        if (!$vfile) {
            return abort(404, 'Выкройка не найдена');
        }

        // Валидация email (можно доработать под свою логику)
        $email = $request->input('email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response('Не указан или некорректен email', 400);
        }

        // Создаём заказ
        $order = new \App\Models\Order();
        $order->vfile_id = $vfile->id;
        $order->status = 'pending';
        $order->email = $email;
        $order->save();

        // Генерируем ссылку на оплату
        $paymentUrl = $this->prodamusService->generatePaymentUrl($order);
        $order->payment_url = $paymentUrl;
        $order->save();

        // Редиректим пользователя на платёжную страницу
        return redirect($paymentUrl);
    }

    /**
     * Обработка webhook от Prodamus
     */
    public function webhook(Request $request)
    {
        $data = $request->all();
        $secretKey = config('services.prodamus.secret_key');
        $sign = $data['sign'] ?? '';
        $expectedSign = $this->checkSign($data, $secretKey);

        if ($sign !== $expectedSign) {
            \Log::warning('Prodamus Webhook: Invalid signature', ['received' => $sign, 'expected' => $expectedSign]);
            return response('Invalid signature', 403);
        }

        $orderId = $data['order_id'] ?? null;
        $order = \App\Models\Order::find($orderId);

        if (!$order) {
            \Log::error("Prodamus Webhook: Order not found", ['order_id' => $orderId]);
            return response('Order not found', 404);
        }

        if (($data['status'] ?? '') === 'paid') {
            $order->status = 'paid';
            $order->save();

            $vfile = $order->vfile;
            $measurements = json_decode($order->pattern_details, true);
            $pdfPath = app(\App\Services\ValentinaService::class)->generatePdf($vfile, $measurements);
            $order->pdf_path = $pdfPath;
            $order->save();
        }

        return response('OK', 200);
    }

    private function checkSign(array $data, string $secretKey): string
    {
        ksort($data);
        unset($data['sign']);
        return md5(http_build_query($data) . $secretKey);
    }
} 