<?php

namespace App\Models;

use App\Models\PatternParameter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vfile extends Model
{
    use HasFactory;

	protected $fillable = [

		'slug',
		'title',
		'short',
		'content',
		'price',
		'val_file',
		'vit_file',
		'vit_data',
		'image',

	];

	public static function generatePDF($name, $destination, $vit_file, $val_file)
    {
        // --- НАЧАЛО ВРЕМЕННОЙ ОТЛАДОЧНОЙ ВЕРСИИ ---

        $url = 'http://31.128.38.42:37085/make';

        // 1. Убедимся, что файлы, которые мы собираемся отправить, существуют
        if (!file_exists($vit_file)) {
            dd('ОШИБКА ОТЛАДКИ: .vit файл не найден по пути: ' . $vit_file);
        }
        if (!file_exists($val_file)) {
            dd('ОШИБКА ОТЛАДКИ: .val файл не найден по пути: ' . $val_file);
        }

        // 2. Готовим данные для отправки. Для отправки файлов через POST
        // нужно использовать специальный класс CURLFile.
        $post_data = [
            'name' => $name,
            'destination' => $destination,
            'format' => 1,
            'page' => 4,
            'vit' => new \CURLFile($vit_file),
            'val' => new \CURLFile($val_file),
        ];

        // 3. Выполняем запрос к сервису с помощью библиотеки cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Вернуть ответ, а не выводить в браузер
        curl_setopt($ch, CURLOPT_POST, true); // Указываем, что это POST-запрос
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // Передаем наши данные
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Таймаут на соединение 10 секунд
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Таймаут на выполнение запроса 30 секунд

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Получаем код ответа (200, 404, 500 и т.д.)
        $curl_error = curl_error($ch); // Получаем текст ошибки, если cURL не смог соединиться
        curl_close($ch);

        // 4. Выводим ВСЮ информацию, которую нам удалось собрать, и останавливаем скрипт
        dd([
            'http_status_code' => $http_code,
            'curl_connection_error' => $curl_error,
            'service_response_body' => $response
        ]);

        // --- КОНЕЦ ВРЕМЕННОЙ ОТЛАДОЧНОЙ ВЕРСИИ ---
    }
	/**
     * Определяем связь "один ко многим" (hasMany):
     * Одна "Выкройка" (Vfile) может иметь много "Параметров" (PatternParameter).
     * Это позволит нам легко получать список всех параметров для выкройки: $vfile->parameters
     */
    public function parameters()
    {
        return $this->hasMany(PatternParameter::class);
    }

    /**
     * Создает временный .vit файл с мерками пользователя,
     * вызывает статический генератор PDF и затем удаляет временный файл.
     *
     * @param array $measurements
     * @return string|bool
     */
    public function generateCustomPDF(array $measurements)
    {
        // 1. Создаем временный .vit файл на основе оригинального
        $originalVitPath = $this->vit_file;
        $tempVitPath = storage_path('app/vfiles/temp_' . uniqid() . '.vit');

        if (!copy($originalVitPath, $tempVitPath)) {
            throw new \Exception('Не удалось создать временный файл мерок.');
        }

        // 2. Записываем мерки пользователя в этот временный файл (магия с XML)
        $vitXml = new \SimpleXMLElement(file_get_contents($tempVitPath));

        foreach ($measurements as $name => $value) {
            // Ищем в XML тег <increment> с нужным нам именем
            $incrementNode = $vitXml->xpath("//increment[@name='{$name}']");
            if (isset($incrementNode[0])) {
                // Если нашли - меняем его значение
                $incrementNode[0][0] = (float)$value;
            }
        }

        // Сохраняем измененный XML обратно во временный файл
        $vitXml->asXML($tempVitPath);

        // 3. Вызываем старый генератор, но с путем к нашему новому файлу
        try {
            // В качестве $name и $destination передаем уникальные идентификаторы
            $pdfData = self::generatePDF(
                uniqid('pattern_'),
                uniqid('dest_'),
                $tempVitPath, // <-- Самое главное: передаем временный .vit
                $this->val_file
            );
        } finally {
            // 4. В любом случае удаляем временный файл после использования
            if (file_exists($tempVitPath)) {
                unlink($tempVitPath);
            }
        }

        return $pdfData;
    }

}
