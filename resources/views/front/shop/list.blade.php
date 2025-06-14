@extends('layouts.front.layout')

@section('content')
	<section class="blog">
		<div class="breadcrumbs">
			<ul>
				<li>
					<a href="{{ route('home') }}" title="Главная">Главная</a>
				</li>
				<li class="active">Магазин</li>
			</ul>
		</div>
		<div class="block_content">
			<h1>Каталог выкроек</h1>
			@if ($products->count())
				<div class="grid grid_items">
					@foreach ($products as $product)
						<div class="grid_item">
							<a href="{{ route('blog.single', $product->slug) }}" title="{{ $product->title }}">
								<div class="item_img" style="background-image: url({{ Storage::url($product->image) }});"></div>
							</a>
							<div class="item_info">
								<a href="{{ route('blog.single', $product->slug) }}" title="{{ $product->title }}" class="post_title">{{ $product->title }}</a>
								<div class="item_short">{!! $product->short !!}</div>
								<div class="item_price">
									{{ number_format($product->price, 2, '.', '') }} &#8381;
								</div>
								<a href="{{ route('constructor.use', $product->id) }}" class="btn btn_smaller">Конструктор</a>
							</div>
						</div>
					@endforeach
				</div>
			@else
				<div class="alert alert_empty">Блог пуст!</div>
			@endif
		</div>
	</section>
@endsection