@extends('layouts.front.layout')

@section('content')
	<section class="construct">
		<h1>Создать выкройку</h1>
		<div class="steps">
			<ul>
				<li>
					<div><span>1</div></span>
					<p>Выберите тип нужного изделия или выберите из <a href="{{ route('shop') }}">каталога</a></p>
				</li>
				<li>
					<div><span>2</div></span>
					<p>Укажите собственные размеры, следуя инструкции</p>
				</li>
				<li>
					<div><span>3</span></div>
					<p>Получите готовую для печати выкройку в формате А4!</p>
				</li>
			</ul>
		</div>
		<div class="desk">
			<div class="preview">
				<div class="inside"></div>
			</div>
			<div class="panel">
				<form id="construct" action="{{ route('vfiles.generate', $vfile->slug) }}" method="POST">
					@csrf
					<div class="ctitle">
						<h2>Изделие: <span>{{ $vfile->title }}</span></h2>
						<a href="#" class="selectVType">Выбрать тип изделия</a>
					</div>
					@if ($props)
						@for ($a = 0; $a < sizeof($props); $a++)
							<div class="cgroup">
								@foreach ($props[$a] as $prop)
									<div class="form_group">
										<label for="p_{{ $prop['id'] }}">
											{{ $prop['label'] }}
											@if (isset($prop['hint']))
												<a href="#" title="{{ $prop['hint'] }}" class="hint">?</a>
											@endif
										</label>
										<input id="p_{{ $prop['id'] }}" type="text" name="measurements[{{ $prop['key'] }}]" data-default="{{ $prop['default'] }}" value="{{ $prop['default'] }}" class="vfile_prop" />
									</div>
								@endforeach
							</div>
						@endfor
					@endif
					<div class="btns">
						<button type="submit" class="btn btn_primary">Построить выкройку</button>
						<a href="#" class="btn btn_gray">Очистить</a>
					</div>
				</form>
			</div>
		</div>
	</section>
@endsection