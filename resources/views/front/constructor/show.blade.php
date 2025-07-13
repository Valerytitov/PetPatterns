@extends('layouts.front.layout')

@section('content')
<section class="constructor">
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="{{ route('shop') }}" title="Магазин">Магазин</a>
            </li>
            <li>
                <a href="{{ route('vfiles.show', $vfile) }}" title="{{ $vfile->title }}">{{ $vfile->title }}</a>
            </li>
            <li class="active">Конструктор</li>
        </ul>
    </div>

    <div class="constructor_container">
        <div class="constructor_img_block">
            @if ($vfile->image)
                <img src="{{ Storage::url($vfile->image) }}" alt="{{ $vfile->title }}" />
            @endif
        </div>
        <div class="constructor_form_block">
            <h1>{{ $vfile->title }}</h1>
            <div class="measurements_form">
                {{-- Блок с ошибками --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h4>Пожалуйста, исправьте ошибки:</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('vfiles.generate', $vfile) }}" method="POST" class="form">
                    @csrf
                    {{-- Проверяем, есть ли у выкройки параметры --}}
                    @if(!empty($vfile->parameters) && collect($vfile->parameters)->isNotEmpty())
                        <div class="measurements_grid">
                            @foreach($vfile->parameters as $parameter)
                                <div class="form_group">
                                    <label for="{{ $parameter->variable_name }}">
                                        {{ $parameter->description }}
                                        <small class="text-muted">{{ $parameter->name }}</small>
                                    </label>
                                    <input 
                                        type="number" 
                                        step="0.1" 
                                        class="form_control" 
                                        name="measurements[{{ $parameter->variable_name }}]" 
                                        id="{{ $parameter->variable_name }}" 
                                        value="{{ old('measurements.' . $parameter->variable_name) }}"
                                        placeholder="Введите значение (см)" 
                                        required
                                    >
                                </div>
                            @endforeach
                        </div>
                        <div class="form_actions form_actions_row">
                            <button type="submit" class="btn btn_primary">Построить выкройку</button>
                            <button type="reset" class="btn btn_gray">Очистить</button>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Для этой выкройки не указаны параметры. Пожалуйста, свяжитесь с администратором.
                        </div>
                    @endif
                </form>
            </div>
            <div class="instr" style="margin-top:32px;">
                <h2>Инструкция</h2>
                {!! $vfile->content !!}
            </div>
        </div>
    </div>
</section>
@endsection 