<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function generate(Request $request, Vfile $vfile)
    {
        // 1. Получаем пользовательские мерки из запроса
        $userMeasurements = $request->input('measurements', []);

        // 2. Создаем XML-содержимое для .vit файла
        $vitContent = '<measurements>';
        foreach ($userMeasurements as $name => $value) {
            // Предполагаем, что единицы измерения по умолчанию - см. Если нужны другие, это должно быть в UI/параметрах.
            $vitContent .= '<measurement name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" unit="cm"/>';
        }
        $vitContent .= '</measurements>';

        // 3. Сохраняем временный .vit файл
        $tempVitFilename = 'temp_measures_' . uniqid() . '.vit';
        Storage::disk('public')->put('temp/' . $tempVitFilename, $vitContent);
        $tempVitFilePath = Storage::disk('public')->path('temp/' . $tempVitFilename);

        // 4. Получаем путь к .val файлу выкройки
        $valFilePath = Storage::disk('public')->path($vfile->val_file);

        // 5. Определяем папку для сохранения PDF
        $outputDir = storage_path('app/public/generated_pdfs');
        if (!Storage::disk('public')->exists('generated_pdfs')) {
            Storage::disk('public')->makeDirectory('generated_pdfs');
        }
        $outputPdfBasename = 'pattern_' . uniqid();
        $outputPdfPath = $outputDir . '/' . $outputPdfBasename . '.pdf';

        // 6. Формируем команду для Valentina
        $valentinaExecutable = '/usr/local/bin/seamly2dcl'; // Путь к скомпилированному движку
        $command = "{$valentinaExecutable} -platform offscreen --format 1 --basename {$outputPdfBasename} --mfile {$tempVitFilePath} --destination {$outputDir} {$valFilePath}";

        // 7. Выполняем команду
        $commandOutput = shell_exec($command . ' 2>&1'); // Перенаправляем stderr в stdout для отладки

        // 8. Обрабатываем результат
        if (file_exists($outputPdfPath)) {
            // Удаляем временный .vit файл
            Storage::disk('public')->delete('temp/' . $tempVitFilename);

            // Возвращаем PDF-файл пользователю
            return response()->download($outputPdfPath)->deleteFileAfterSend(true);
        } else {
            // Удаляем временный .vit файл, если он был создан, но PDF не сгенерировался
            Storage::disk('public')->delete('temp/' . $tempVitFilename);
            
            // Логируем ошибку и возвращаем сообщение
            \Log::error('PDF generation failed: ' . $commandOutput);
            return back()->withErrors(['error' => 'Не удалось сгенерировать PDF. Пожалуйста, попробуйте еще раз. Детали: ' . $commandOutput]);
        }
    }
}
