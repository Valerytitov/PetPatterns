<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePatternPdfJob;
use App\Models\Vfile;
use Illuminate\Http\Request;
use App\Services\ValentinaService;
use Illuminate\Support\Facades\Log; // Добавим для логирования

class VfileController extends Controller
{
    protected ValentinaService $valentinaService;

    public function __construct(ValentinaService $valentinaService)
    {
        $this->valentinaService = $valentinaService;
    }

    /**
     * Отображает страницу выкройки с формой ввода мерок
     */
    public function show(Vfile $vfile)
    {
        return view('front.patterns.single', compact('vfile'));
    }
    
    /**
     * Создаёт заказ и редиректит пользователя на оплату через Prodamus
     */
    public function generatePdf(Request $request, Vfile $vfile)
    {
        Log::info('VfileController::generatePdf started', [
            'request_data' => $request->all(),
            'vfile_id' => $vfile->id
        ]);

        try {
            $validated = $request->validate([
                'measurements' => 'required|array',
                'measurements.*' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (is_numeric($value) && $value >= 1) { return; }
                        if (is_string($value) && str_starts_with($value, '@')) { return; }
                        $fail('Поле должно содержать число (например, 25.5) или формулу (например, @a_длина).');
                    }
                ],
                'email' => 'required|email',
            ]);

            Log::info('MEASUREMENTS', ['data' => $validated['measurements']]);

            // Создаём заказ
            $order = \App\Models\Order::create([
                'vfile_id' => $vfile->id,
                'status' => 'pending',
                'pattern_details' => $validated['measurements'],
                'email' => $validated['email'],
                'sum' => $vfile->price,
            ]);

            Log::info('ORDER CREATED', ['order' => $order->toArray()]);

            // --- Если цена 0, показываем страницу ожидания генерации PDF ---
            if (floatval($vfile->price) == 0.0) {
                // Сохраняем заказ, но не генерируем PDF синхронно
                return view('front.payment.processing', [
                    'order' => $order,
                    'vfile' => $vfile,
                ]);
            }

            // Генерируем ссылку на оплату через ProdamusService
            $paymentUrl = app(\App\Services\ProdamusService::class)->generatePaymentUrl($order);
            $order->payment_url = $paymentUrl;
            $order->save();

            Log::info('Payment URL generated', ['payment_url' => $paymentUrl]);

            // Редиректим пользователя на оплату
            return redirect($paymentUrl);

        } catch (\Exception $e) {
            Log::error('Error in generatePdf', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Для бесплатных заказов: завершение генерации PDF и success
     */
    public function freePaymentSuccess(\App\Models\Order $order)
    {
        $vfile = $order->vfile;
        Log::info('FREE PAYMENT: order from DB', ['order' => $order->toArray()]);
        $measurements = $order->pattern_details;
        Log::info('FREE PAYMENT: measurements', ['measurements' => $measurements]);
        if (!is_array($measurements) || empty($measurements)) {
            return response('Ошибка: не удалось получить мерки для генерации PDF. Попробуйте оформить заказ заново.', 500);
        }
        if (!$order->pdf_path || !file_exists(public_path('storage/generated/' . $vfile->slug . '_pattern.pdf'))) {
            // Генерируем PDF, если ещё не был сгенерирован
            $pdfPath = $this->valentinaService->generatePdf($vfile, $measurements);
            $order->status = 'paid';
            // Сохраняем относительный путь для PDF
            $order->pdf_path = 'generated/' . $vfile->slug . '_pattern.pdf';
            $order->save();
        }
        $pdfUrl = asset('storage/' . $order->pdf_path);
        return view('front.payment.success', [
            'order' => $order,
            'vfile' => $vfile,
            'pdf_url' => $pdfUrl,
        ]);
    }
}