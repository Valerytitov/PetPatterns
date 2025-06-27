@extends('layouts.front.layout')

@section('content')
	<section class="construct">
		<h1>Конструктор выкроек</h1>
		<div class="desk">
			<div class="preview">
				@if ($vfile->image)
					<div class="item_img" style="background-image: url({{ Storage::url($vfile->image) }});"></div>
				@endif
			</div>
			<div class="panel">
				@if(session('success'))
					<div class="alert alert-success alert-dismissible" role="alert">
						{{ session('success') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif
				@if(session('error'))
					<div class="alert alert-danger alert-dismissible" role="alert">
						{{ session('error') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif
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
											{{ $prop['prop_title'] }}
											@if (isset($prop['prop_hint']))
												<a href="#" title="{{ $prop['prop_hint'] }}" class="hint">?</a>
											@endif
										</label>
										<input id="p_{{ $prop['id'] }}" type="text" name="measurements[{{ $prop['prop_key'] }}]" data-default="{{ $prop['default'] ?? '' }}" value="{{ $prop['default'] ?? '' }}" class="vfile_prop" />
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