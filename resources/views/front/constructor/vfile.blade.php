@extends('layouts.front.layout')

@section('content')
	<section class="construct">
		<h1>Конструктор выкроек</h1>
		<div class="desk">
			<div class="preview">
				<div class="inside"></div>
			</div>
			<div class="panel">
				<form id="formVfile" action="{{ route('vfiles.generate', $vfile->slug) }}" method="POST">
					@csrf
					<div class="ctitle">
						<h2><span>{{ $vfile->title }}</span></h2>
					</div>
					@if ($props)
						@for ($a = 0; $a < sizeof($props) - 1; $a++)
							<div class="cgroup">
								@foreach ($props[$a] as $prop)
									<div class="form_group">
										<label for="p_{{ $prop['id'] }}">
											{{ $prop['label'] }}
											@if ($prop['hint'])
												<a href="#" title="{{ $prop['hint'] }}" class="hint">?</a>
											@endif
										</label>
										<input id="p_{{ $prop['id'] }}" type="text" name="measurements[{{ $prop['key'] }}]" data-default="{{ $prop['default'] }}" value="{{ $prop['default'] }}" class="vfile_prop" />
									</div>
								@endforeach
							</div>
						@endfor
					@endif
					<div class="vfile_price">
						<p>Цена: <span>{{ number_format($vfile->price, 2, '.', '') }} &#8381;</span></p>
					</div>
					<div class="btns">
						<button id="doVfile" type="submit" class="btn btn_primary">Построить выкройку</button>
						<button id="resetVfile" type="button" class="btn btn_gray">Очистить</a>
					</div>
				</form>
			</div>
		</div>
		<div class="instr">
			<h2>Инструкция</h2>
			{!! $vfile->content !!}
		</div>
	</section>
@endsection