<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vfile;
use App\Services\ValentinaService; // <-- Добавляем наш новый сервис
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VfileController extends Controller
{
    /**
     * Отображает страницу конкретной выкройки.
     */
    public function show(Vfile $vfile)
    {
        $title = $vfile->title;
        return view('front.patterns.single', compact('vfile', 'title'));
    }

    /**
     * Генерирует PDF-файл выкройки на основе пользовательских параметров.
     */
    public function generate(Request $request, Vfile $vfile, ValentinaService $valentina)
    {
        $validated = $request->validate([
            'measurements' => ['required', 'array'],
            'measurements.*' => ['required'],
        ]);

        $outputPdfPath = $valentina->generatePdf($vfile, $validated['measurements']);

        if ($outputPdfPath) {
            return response()->download($outputPdfPath)->deleteFileAfterSend(true);
        } else {
            // Логирование уже происходит внутри сервиса, здесь просто возвращаем ошибку
            return back()->withErrors(['error' => 'Не удалось сгенерировать PDF. Пожалуйста, обратитесь в поддержку.']);
        }
    }
}