<?php

namespace App\Http\Controllers;

use App\Models\Vfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Отображает страницу конкретной выкройки с формой для ввода мерок.
     *
     * @param  \App\Models\Vfile  $vfile
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Vfile $vfile)
    {
        // Загружаем параметры (мерки), которые мы настроили в админ-панели.
        // Это нужно, чтобы мы могли построить для них поля ввода.
        $vfile->load('parameters');

        // Передаем данные о выкройке и ее параметрах в шаблон (view).
        return view('vfiles.show', compact('vfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vfile $vfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vfile $vfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vfile $vfile)
    {
        //
    }

    /**
     * Обрабатывает форму с мерками и генерирует PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vfile  $vfile
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function generate(Request $request, Vfile $vfile)
    {
        // --- 1. Динамическая валидация ---
        // Мы не знаем заранее, какие мерки нужны, поэтому строим правила "на лету"
        // на основе параметров, которые мы задали для выкройки в админке.
        $vfile->load('parameters'); // Убедимся, что параметры выкройки загружены

        $rules = [];
        $messages = [];

        foreach ($vfile->parameters as $parameter) {
            $ruleKey = 'measurements.' . $parameter->variable_name;
            $rules[$ruleKey] = 'required|numeric|min:1'; // Поле обязательно, должно быть числом, не меньше 1

            // Создаем понятные сообщения об ошибках
            $messages[$ruleKey . '.required'] = 'Поле "' . $parameter->display_name . '" обязательно для заполнения.';
            $messages[$ruleKey . '.numeric'] = 'Значение в поле "' . $parameter->display_name . '" должно быть числом.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput(); // withInput() вернет пользователя на форму с уже введенными данными
        }


        // --- 2. Вызов сервиса генерации PDF ---
        // Мы предполагаем, что ключевой метод generatePDF() принимает массив с мерками.
        // Оборачиваем в try...catch на случай, если сервис генерации выдаст ошибку.
        try {
            $measurements = $request->input('measurements');
            // Вызываем наш новый "умный" метод, а не старый
            $pdfData = $vfile->generateCustomPDF($measurements);
        } catch (\Exception $e) {
            // Если что-то пошло не так внутри generatePDF, мы поймаем ошибку
            // и вернем пользователя назад с понятным сообщением.
            return redirect()->back()->with('error', 'Не удалось сгенерировать PDF. Ошибка сервиса: ' . $e->getMessage())->withInput();
        }


        // --- 3. Отдаем готовый PDF пользователю ---
        // В будущем здесь будет логика оплаты, а пока - сразу отдаем файл.
        $fileName = $vfile->slug . '-' . time() . '.pdf';

        return response($pdfData)
            ->header('Content-Type', 'application/pdf')
            // 'inline' попытается показать PDF в браузере, 'attachment' - сразу предложит скачать.
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }
}
