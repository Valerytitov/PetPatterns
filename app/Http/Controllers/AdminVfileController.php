<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

use App\Models\Vfile;
use App\Models\Prop;

class AdminVfileController extends Controller {

    public function index(Request $request) {

		$list = Vfile::orderBy('id', 'desc')->get();

		if ($list->count()) {
			$list = $list->map(function($rec) {
				$rec->props = $rec->vit_data;
				return $rec;
			});
		}

		$return = compact('list');

        return view('admin/vfiles/index', $return);

    }

// Замените ваш текущий метод form() на этот:

    public function form(Request $request, $id = 0)
    {
        $rec = Vfile::findOrNew($id);

        // Если это POST-запрос (пользователь нажал "Сохранить")
        if ($request->isMethod('post')) {
            $rules = [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:vfiles,slug,' . $rec->id,
                'price' => 'required|numeric',
                'short' => 'required|string',
                'content' => 'required|string',
                'image' => $rec->exists ? 'nullable|image|max:2048' : 'required|image|max:2048',
                'val_file' => $rec->exists ? 'nullable' : 'required',
                'vit_file' => $rec->exists ? 'nullable' : 'required',
            ];

            $validatedData = $request->validate($rules);

            // Вручную обрабатываем параметры, если это страница редактирования
            if ($rec->exists && $request->has('parameters')) {
                 $validatedData['parameters'] = $request->input('parameters');
            }

            // Логика загрузки файлов
            if ($request->hasFile('image')) {
                if ($rec->image) { Storage::disk('public')->delete($rec->image); }
                $validatedData['image'] = $request->file('image')->store('vfiles/images', 'public');
            }
            if ($request->hasFile('val_file')) {
                if ($rec->val_file) { Storage::disk('public')->delete($rec->val_file); }
                $validatedData['val_file'] = $request->file('val_file')->store('vfiles/val', 'public');
            }
            if ($request->hasFile('vit_file')) {
                if ($rec->vit_file) { Storage::disk('public')->delete($rec->vit_file); }
                $validatedData['vit_file'] = $request->file('vit_file')->store('vfiles/vit', 'public');

                // Парсим загруженный VIT файл и сохраняем его данные в vit_data
                $uploadedVitFilePath = Storage::disk('public')->path($validatedData['vit_file']);
                $parsedVitData = \App\Helpers\VFile::parseVIT($uploadedVitFilePath);

                if ($parsedVitData) {
                    $validatedData['vit_data'] = json_encode($parsedVitData, JSON_UNESCAPED_UNICODE);
                } else {
                    // Если парсинг не удался, можно добавить ошибку валидации
                    return back()->withErrors(['vit_file' => 'Не удалось распознать данные из файла мерок (VIT). Проверьте его формат.'])->withInput();
                }
            }

            // Заполняем модель и сохраняем
            $rec->fill($validatedData)->save();

            return redirect()->route('admin.vfiles')->with('success', 'Выкройка успешно сохранена!');
        }

        // Если это GET-запрос, просто показываем форму как и раньше
        $title = $rec->exists ? 'Редактирование выкройки' : 'Новая выкройка';
        return view('admin.vfiles.form', compact('rec', 'title', 'id'));
    }

    public function props($id, Request $request) {

        $rec = Vfile::find($id);
        if (!$rec) {
            return redirect()->route('admin.vfiles')->with('error', 'Выкройка не найдена!');
        }

        $props = \App\Helpers\VFile::buildProps($rec->vit_data);
        $title = $rec->title.' - Выкройки';
        $return = compact('id', 'rec', 'title');

        return view('admin/vfiles/props', $return);

    }

}
