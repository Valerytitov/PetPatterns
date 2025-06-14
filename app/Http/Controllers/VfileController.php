<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    public function generate(Request $request, Vfile $vfile)
    {
        // Удаляем отладочный вывод для всех данных запроса
        // dd($request->all());

        // 1. Получаем пользовательские мерки из запроса
        $userMeasurements = $request->input('measurements', []);
        \Log::info('Received user measurements:', $userMeasurements);

        // 2. Создаем XML-содержимое для .vit файла
        $vitContent = '<measurements>';
        foreach ($userMeasurements as $name => $value) {
            // Предполагаем, что единицы измерения по умолчанию - см. Если нужны другие, это должно быть в UI/параметрах.
            $vitContent .= '<measurement name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" unit="cm"/>';
        }
        $vitContent .= '</measurements>';
        \Log::info('Generated .vit content: ' . $vitContent);

        // 3. Сохраняем временный .vit файл
        $tempVitFilename = 'temp_measures_' . uniqid() . '.vit';
        Storage::disk('public')->put('temp/' . $tempVitFilename, $vitContent);
        $tempVitFilePath = Storage::disk('public')->path('temp/' . $tempVitFilename);
        \Log::info('Temp .vit file path: ' . $tempVitFilePath);

        // 4. Получаем путь к .val файлу выкройки
        if (empty($vfile->val_file)) {
            return back()->withErrors(['error' => 'Файл выкройки (.val) не указан для этой выкройки. Пожалуйста, загрузите его через админ-панель.']);
        }
        if (!Storage::disk('public')->exists($vfile->val_file)) {
            return back()->withErrors(['error' => 'Файл выкройки (.val) не найден в хранилище. Пожалуйста, проверьте его наличие.']);
        }

        $valFilePath = Storage::disk('public')->path($vfile->val_file);
        \Log::info('Val file path: ' . $valFilePath);

        // 5. Определяем папку для сохранения PDF
        $outputDir = storage_path('app/public/generated_pdfs');
        if (!Storage::disk('public')->exists('generated_pdfs')) {
            Storage::disk('public')->makeDirectory('generated_pdfs');
        }
        $outputPdfBasename = 'pattern_' . uniqid();
        $outputPdfPath = $outputDir . '/' . $outputPdfBasename . '.pdf';
        \Log::info('Output PDF directory: ' . $outputDir);
        \Log::info('Output PDF basename: ' . $outputPdfBasename);

        // 6. Формируем команду для Valentina
        $valentinaExecutable = '/usr/local/bin/valentina'; // Путь к скомпилированному движку
        $command = "{$valentinaExecutable} -platform offscreen --format 1 --basename {$outputPdfBasename} --mfile {$tempVitFilePath} --destination {$outputDir} {$valFilePath}";
        \Log::info('Valentina command: ' . $command);

        // 7. Выполняем команду
        $commandOutput = shell_exec($command . ' 2>&1'); // Перенаправляем stderr в stdout для отладки
        \Log::info('Valentina command output: ' . $commandOutput);

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
