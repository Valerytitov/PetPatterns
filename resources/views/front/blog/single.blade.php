@extends('layouts.front.layout')

@section('content')
	<section class="blog">
		<div class="breadcrumbs">
			<ul>
				<li>
					<a href="{{ route('home') }}" title="Главная">Главная</a>
				</li>
				<li>
					<a href="{{ route('blog') }}" title="Блог">Блог</a>
				</li>
				<li class="active">{{ $post->title }}</li>
			</ul>
		</div>
		<div class="block_content">
			<h1>{{ $post->title }}</h1>
			<div class="post_content">{!! $post->content !!}</div>
		</div>
		<a href="{{ route('blog') }}" title="Блог" class="btn">Вернуться в Блог</a>
	</section>
	@if ($posts->count())
	<section class="blog mt45">
		<h2>Еще почитать</h2>
		<div class="grid grid_items">
			@foreach ($posts as $post)
				<div class="grid_item">
					<a href="{{ route('blog.single', $post->slug) }}" title="{{ $post->title }}">
						<div class="item_img" style="background-image: url({{ $post->image }});"></div>
					</a>
					<div class="item_info">
						<a href="{{ route('blog.single', $post->slug) }}" title="{{ $post->title }}" class="post_title">{{ $post->title }}</a>
						<div class="item_short">{!! $post->short !!}</div>
					</div>
				</div>
			@endforeach
		</div>
	</section>
	@endif
@endsection