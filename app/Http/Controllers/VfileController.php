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
        return view('front.constructor.show', compact('vfile'));
    }
    
    /**
     * Валидирует данные и отправляет задачу на генерацию PDF в очередь.
     */
    public function generatePdf(Request $request, Vfile $vfile)
    {
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
        ]);
        
        $measurements = $validated['measurements'];

        try {
            $pdfPath = $this->valentinaService->generatePdf($vfile, $measurements);
            // Путь для ссылки относительно public
            $publicPath = '/storage/generated/' . basename($pdfPath);
            return back()->with('success', 'PDF успешно сгенерирован! <a href="' . $publicPath . '" target="_blank">Скачать PDF</a>');
        } catch (\Throwable $e) {
            Log::error('Ошибка генерации PDF: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Ошибка генерации PDF: ' . $e->getMessage());
        }
    }
}