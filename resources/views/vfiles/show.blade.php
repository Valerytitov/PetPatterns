{{-- resources/views/vfiles/show.blade.php --}}

{{-- Этот шаблон предполагает, что у вас есть основной макет 'layouts.app'. --}}
{{-- Если ваш главный макет называется иначе (например, layouts.main), измените эту строку. --}}
@extends('layouts.app')

@section('title', $vfile->title)

@section('content')
<div class="container mt-5">
    <div class="row">
        {{-- Левая колонка: Информация о выкройке --}}
        <div class="col-md-6">
            <h1>{{ $vfile->title }}</h1>

            @if($vfile->image)
                <img src="{{ asset($vfile->image) }}" class="img-fluid rounded mb-3" alt="{{ $vfile->title }}">
            @endif

            <p class="lead">{{ $vfile->short }}</p>
            <div>
                {!! $vfile->content !!}
            </div>
        </div>

        {{-- Правая колонка: Форма для ввода мерок --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Введите мерки вашего питомца</h3>
                </div>
                <div class="card-body">
                    {{-- НАЧАЛО БЛОКА ОТОБРАЖЕНИЯ ОШИБОК --}}
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Пожалуйста, исправьте ошибки:</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                         <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    {{-- КОНЕЦ БЛОКА --}}

                    {{-- Форма будет отправлять данные на маршрут, который мы создадим в следующем шаге --}}
                    <form action="{{ route('vfiles.generate', $vfile) }}" method="POST">
                        {{-- @csrf - это обязательный токен безопасности Laravel для всех POST-форм --}}
                        @csrf

                        {{-- Проверяем, есть ли у выкройки параметры --}}
                        @if($vfile->parameters->isNotEmpty())

                            {{-- В цикле выводим по одному полю для каждой необходимой мерки --}}
                            @foreach($vfile->parameters as $parameter)
                                <div class="form-group mb-3">
                                    <label for="{{ $parameter->name }}">{{ $parameter->description }}</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control" name="measurements[{{ $parameter->variable_name }}]" id="{{ $parameter->variable_name }}" placeholder="Введите значение" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">см</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Техническое имя: `{{ $parameter->name }}`</small>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary w-100">Создать выкройку</button>

                        @else
                            <div class="alert alert-warning">
                                Для этой выкройки не указаны параметры. Пожалуйста, настройте их в административной панели.
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
