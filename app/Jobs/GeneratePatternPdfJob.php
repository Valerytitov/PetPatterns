<?php

namespace App\Jobs;

use App\Models\Vfile;
use App\Services\ValentinaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GeneratePatternPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Создаем Job с необходимыми данными для генерации.
     * Laravel автоматически обработает модель Vfile.
     */
    public function __construct(
        public Vfile $vfile,
        public array $measurements
    ) {}

    /**
     * Этот метод будет выполнен фоновым обработчиком (worker).
     * Внедряем ValentinaService прямо сюда.
     */
    public function handle(ValentinaService $valentinaService): void
    {
        Log::info("Starting PDF generation job for vfile ID: {$this->vfile->id}");

        try {
            // Вызываем наш сервис, который делает всю работу
            $pdfPath = $valentinaService->generatePdf($this->vfile, $this->measurements);

            // Здесь можно добавить логику после успешной генерации
            // Например, сохранить путь к файлу в базу данных или отправить уведомление пользователю
            Log::info("Successfully generated PDF for vfile ID: {$this->vfile->id} at path: {$pdfPath}");

        } catch (\Throwable $e) {
            // Если в процессе генерации произошла ошибка, логируем ее
            Log::error("Failed PDF generation job for vfile ID: {$this->vfile->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Можно также уведомить пользователя об ошибке
        }
    }
}
