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
				$rec->props = json_decode($rec->vit_data);
				return $rec;
			});
		}

		$return = compact('list');

        return view('admin/vfiles/index', $return);

    }

// Замените ваш текущий метод form() на этот:

    public function form($id, Request $request) {

        $title = 'Добавить ';
        $rec = new Vfile;

        if ($id) {

            $rec = Vfile::find($id);
            if (!$rec) {
                return redirect()->route('admin.vfiles')->with('error', 'Запись не найдена');
            }

            // <<< НАЧАЛО ИЗМЕНЕНИЯ #1: ЗАГРУЗКА ПАРАМЕТРОВ >>>
            // Загружаем связанные параметры для существующей выкройки.
            // Теперь в шаблоне будут доступны $rec->parameters
            $rec->load('parameters');
            // <<< КОНЕЦ ИЗМЕНЕНИЯ #1 >>>

            $title = 'Редактировать ';

        }

        $title .= 'выкройку';

        if ($request->isMethod('post')) {

            // Добавляем 'подстраховку' на случай, если slug не был сгенерирован на фронтенде
            if (empty($request->slug) && !empty($request->title)) {
                $request->merge([
                    'slug' => \Illuminate\Support\Str::slug($request->title)
                ]);
            }

            $rules = [

                'slug' => 'required',
                'title' => 'required',
                'price' => 'required',
                'short' => 'required',
                'content' => 'required',

                // <<< НАЧАЛО ИЗМЕНЕНИЯ #2: ВАЛИДАЦИЯ ПАРАМЕТРОВ >>>
                // Добавляем правила для наших новых полей с параметрами.
                'parameters' => 'nullable|array',
                'parameters.*.name' => 'required_with:parameters|string|max:255',
                'parameters.*.description' => 'nullable|string|max:255',
                // <<< КОНЕЦ ИЗМЕНЕНИЯ #2 >>>

            ];

            $msgs = [

                'slug.required' => 'Пожалуйста, укажите URL выкройки',
                'slug.unique' => 'Выкройка с таким URL уже существует, выберите другое!',
                'title.required' => 'Пожалуйста, укажите заголовок выкройки / название выкройки',
                'short.required' => 'Пожалуйста, введите краткое описание выкройки',
                'content.required' => 'Пожалуйста, введите описание выкройки / инструкцию',
                'price.required' => 'Пожалуйста, укажите стоимость выкройки',
                'image.required' => 'Пожалуйста, выберите изображение выкройки',
                'val_file.required' => 'Файл .val обязателен!',
                'vit_file.required' => 'Файл .vit обязателен!',

            ];

            if (!$id) {

                $rules['slug'] .= '|unique:vfiles';
                $rules['image'] = 'required|file';
                $rules['val_file'] = 'required|file';
                $rules['vit_file'] = 'required|file';

            }

            $validator = Validator::make($request->all(), $rules, $msgs);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $image = $rec->image;
            $val_store = $rec->val_file;
            $vit_store = $rec->vit_file;

            /* Изображение */
            if ($request->file('image')) {

                $image_ext = $request->image->getClientOriginalExtension();
                $image_file = time().'.'.$image_ext;
                $image = '/vf/'.$request->file('image')->storeAs('', $image_file, ['disk' => 'vfiles_public']);


            }

            /* .val */
            if ($request->file('val_file')) {

                $val_ext = $request->val_file->getClientOriginalExtension();
                $val_file = time().'.val';
                $val_store = storage_path('app/vfiles/').$request->file('val_file')->storeAs('', $val_file, ['disk' => 'vfiles']);

            }

            /* .vit */
            if ($request->file('vit_file')) {

                $vit_ext = $request->vit_file->getClientOriginalExtension();
                $vit_file = time().'.vit';
                $vit_store = storage_path('app/vfiles/').$request->file('vit_file')->storeAs('', $vit_file, ['disk' => 'vfiles']);

            }

            if (!file_exists($val_store) or !file_exists($vit_store)) {
                return redirect()->back()->with('error', 'Не удалось загрузить .vit / .val файлы!')->withInput();
            }

            /* .vit размеры */
            $vit_data = \App\Helpers\VFile::parseVIT($vit_store);
            if (!is_array($vit_data)) {
                $vit_data = [];
            }

            $vit_data = json_encode($vit_data, JSON_UNESCAPED_UNICODE);

            $data = [

                'slug' => $request->slug,
                'title' => $request->title,
                'short' => $request->short,
                'content' => $request->content,
                'price' => $request->price,
                'val_file' => $val_store,
                'vit_file' => $vit_store,
                'vit_data' => $vit_data,
                'image' => $image,

            ];

            // ВАЖНО: Мы удалили дублирующуюся проверку валидатора отсюда.

            $rec->fill($data);
            $rec->save();

            // <<< НАЧАЛО ИЗМЕНЕНИЯ #3: СОХРАНЕНИЕ ПАРАМЕТРОВ >>>
            // Используем надежную стратегию "удалить старые и записать новые".

            // 1. Удаляем все старые параметры для этой выкройки.
            $rec->parameters()->delete();

            // 2. Создаем новые параметры из данных формы.
            if ($request->has('parameters')) {
                foreach ($request->input('parameters') as $parameterData) {
                    // Пропускаем пустые строки, если пользователь случайно добавил лишнюю.
                    if (empty($parameterData['name'])) {
                        continue;
                    }

                    $rec->parameters()->create([
                        'variable_name' => $parameterData['name'],
                        'display_name'  => $parameterData['description'] ?? $parameterData['name'],
                        'description' => $parameterData['description'] ?? '',
                    ]);
                }
            }
            // <<< КОНЕЦ ИЗМЕНЕНИЯ #3 >>>

            // Мы изменили редирект, чтобы он вел обратно на форму редактирования.
            // Так администратор сразу увидит сохраненные параметры.
            return redirect()->route('admin.vfiles.form', $rec->id)->with('success', 'Выкройка и ее параметры сохранены!');

        }

        $return = compact('id', 'rec', 'title');

        return view('admin/vfiles/form', $return);

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
