@extends('layouts.front.layout')

@section('content')
<section class="constructor_block">
    <h1>Конструктор выкроек</h1>
    @if ($vfile->image)
        <div class="constructor_img">
            <img src="{{ Storage::url($vfile->image) }}" alt="{{ $vfile->title }}" />
        </div>
    @endif
    <form id="formVfile" action="{{ route('vfiles.generate', $vfile->slug) }}" method="POST" class="constructor_form">
        @csrf
        <div class="ctitle">
            <h2><span>{{ $vfile->title }}</span></h2>
        </div>
        @if ($props)
            <div class="cgroup">
                @foreach ($props as $group)
                    @foreach ($group as $prop)
                        <div class="form_group">
                            <label for="p_{{ $prop['id'] }}">
                                {{ $prop['prop_title'] }}
                                @if (isset($prop['prop_hint']))
                                    <a href="#" title="{{ $prop['prop_hint'] }}" class="hint">?</a>
                                @endif
                            </label>
                            <input id="p_{{ $prop['id'] }}" type="text" name="measurements[{{ $prop['prop_key'] }}]" data-default="{{ $prop['default'] ?? '' }}" value="{{ $prop['default'] ?? '' }}" class="vfile_prop" />
                        </div>
                    @endforeach
                @endforeach
            </div>
        @endif
        <div class="form_group">
            <label for="email">Email для получения выкройки:</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="vfile_prop" required placeholder="e-mail" />
        </div>
        <div class="vfile_price">
            <p>Цена: <span>{{ number_format($vfile->price, 2, '.', '') }} &#8381;</span></p>
        </div>
        <div class="btns">
            <button id="doVfile" type="submit" class="btn btn_primary">Построить выкройку</button>
            <button id="resetVfile" type="button" class="btn btn_gray">Очистить</button>
        </div>
    </form>
    <div class="instr">
        <h2>Инструкция</h2>
        {!! $vfile->content !!}
    </div>
</section>
@endsection