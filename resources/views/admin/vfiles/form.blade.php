@extends('layouts.admin.inside')

@section('title', $title)

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.vfiles') }}" class="btn btn-primary">Вернуться к списку</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="{{ $rec->exists ? route('admin.vfiles.update', $rec->id) : route('admin.vfiles.store') }}" method="POST" enctype="multipart/form-data">
            @if ($rec->exists)
                @method('POST') {{-- Laravel ожидает POST для update --}}
            @endif
                @csrf
                <div class="col-md-12">
                    @if ($errors->any())
                        @foreach ($errors->all() as $err)
                            <div class="alert alert-warning">{{ $err }}</div>
                        @endforeach
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="slug">URL: <span class="req">*</span></label>
                                        <input id="patternSlug" type="text" name="slug" value="{{ old('slug', $rec->slug) }}" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">Название выкройки: <span class="req">*</span></label>
                                        <input id="patternTitle" type="text" name="title" value="{{ old('title', $rec->title) }}" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Стоимость выкройки: <span class="req">*</span></label>
                                        <input id="price" type="text" name="price" value="{{ old('price', $rec->price) }}" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="short">Краткое описание: <span class="req">*</span></label>
                                <textarea id="short" name="short" class="form-control">{{ old('short', $rec->short) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="content">Описание: <span class="req">*</span></label>
                                <textarea id="content" name="content" class="form-control" style="height: 450px;">{{ old('content', $rec->content) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="image">Изображение: <span class="req">*</span></label>
                                        @if ($rec->image)
                                            <p>
                                                <img src="{{ asset('storage/' . $rec->image) }}" alt="Изображение выкройки" style="max-width: 200px; height: auto;">
                                            </p>
                                        @endif
                                        <input id="file" type="file" name="image" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="val_file">Файл <strong>.val</strong> <span class="req">*</span>:</label>
                                        <input id="val_file" type="file" name="val_file" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="vit_file">Файл <strong>.vit</strong> <span class="req">*</span>:</label>
                                        <input id="vit_file" type="file" name="vit_file" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Скрипт для автоматической генерации слага --}}
                <script>
                    // Находим наши поля по ID
                    const titleInput = document.getElementById('patternTitle');
                    const slugInput = document.getElementById('patternSlug');

                    // Функция для транслитерации и создания слага
                    function generateSlug(text) {
                        const a = {"Ё":"YO","Й":"I","Ц":"TS","У":"U","К":"K","Е":"E","Н":"N","Г":"G","Ш":"SH","Щ":"SCH","З":"Z","Х":"H","Ъ":"","ё":"yo","й":"i","ц":"ts","у":"u","к":"k","е":"e","н":"n","г":"g","ш":"sh","щ":"sch","з":"z","х":"h","ъ":"","Ф":"F","Ы":"I","В":"V","А":"a","П":"P","Р":"R","О":"O","Л":"L","Д":"D","Ж":"ZH","Э":"E","ф":"f","ы":"i","в":"v","а":"a","п":"p","р":"r","о":"o","л":"l","д":"d","ж":"zh","э":"e","Я":"Ya","Ч":"CH","С":"S","М":"M","И":"I","Т":"T","Ь":"","Б":"B","Ю":"YU","я":"ya","ч":"ch","с":"s","м":"m","и":"i","т":"t","ь":"","б":"b","ю":"yu"};

                        return text.split('').map(function (char) {
                            return a[char] || char;
                        }).join("").replace(/[^\w\d\s-]/g, '') // Удаляем все, кроме букв, цифр, пробелов и дефисов
                                .trim() // Убираем пробелы по краям
                                .replace(/\s+/g, '-') // Заменяем пробелы на дефисы
                                .toLowerCase(); // Приводим к нижнему регистру
                    }

                    // "Слушаем" событие ввода текста в поле "Название"
                    if (titleInput && slugInput) {
                        titleInput.addEventListener('input', function() {
                            // Генерируем слаг и вставляем его в поле "URL"
                            slugInput.value = generateSlug(this.value);
                        });
                    }
                </script>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-footer">
                            <button class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
