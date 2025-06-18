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
				@if ($errors->any())
					<div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px;">
						<strong>Обнаружены ошибки валидации:</strong>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<form id="construct" action="{{ route('vfiles.generate', $vfile->slug) }}" method="POST" x-data="{
					measurements: {{ json_encode(collect($props)->flatten(1)->mapWithKeys(function($prop) { 
						// Если значение по умолчанию - это ссылка, берем значение из другой мерки
						$default = $prop['default'] ?? '';
						if (str_starts_with($default, '@')) {
							$refKey = $default;
							$refProp = collect($props)->flatten(1)->firstWhere('prop_key', $refKey);
							$default = $refProp['default'] ?? '';
						}
						return [$prop['prop_key'] => $default]; 
					}) ) }},
					init() {
						console.log('measurements in init:', this.measurements);
					}
				}">
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
											{{ $prop['prop_title'] }}
											@if (isset($prop['prop_hint']))
												<a href="#" title="{{ $prop['prop_hint'] }}" class="hint">?</a>
											@endif
										</label>
										<input id="p_{{ $prop['id'] }}" type="text" name="measurements[{{ $prop['prop_key'] }}]" x-model="measurements['{{ $prop['prop_key'] }}']" class="vfile_prop" />
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