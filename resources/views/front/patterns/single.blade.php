@extends('layouts.front.layout')

@section('content')
	<section class="pattern_single">
		<div class="breadcrumbs">
			<ul>
				<li>
					<a href="{{ route('shop') }}" title="Магазин">Магазин</a>
				</li>
				<li class="active">{{ $vfile->title }}</li>
			</ul>
		</div>
		<div class="pattern_info">
			<h1>{{ $vfile->title }}</h1>
			@if($vfile->image)
				<div class="pattern_main_img" style="margin-bottom: 24px;">
					<img src="{{ Storage::url($vfile->image) }}" alt="{{ $vfile->title }}" style="max-width:100%;height:auto;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
				</div>
			@endif
			<div class="info_grid">
				<div class="info_img" style="background-image: url({{ Storage::url($vfile->image) }});"></div>
				<div class="info_details">
					<div class="item_short">{!! $vfile->short !!}</div>
					<div class="item_price">
						Цена: <span>{{ number_format($vfile->price, 2, '.', '') }} &#8381;</span>
					</div>
					<a href="{{ route('constructor.use', $vfile->id) }}" class="btn btn_primary">Конструктор</a>
				</div>
			</div>
			<div class="pattern_content">
				<h2>Подробное описание</h2>
				{!! $vfile->content !!}
			</div>
		</div>
	</section>
@endsection 