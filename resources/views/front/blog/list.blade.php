@extends('layouts.front.layout')

@section('content')
	<section class="blog">
		<div class="breadcrumbs">
			<ul>
				<li>
					<a href="{{ route('home') }}" title="Главная">Главная</a>
				</li>
				<li class="active">Блог</li>
			</ul>
		</div>
		<div class="block_content">
			<h1>Журнал "Бери и Шей"</h1>
			@if ($posts->count())
				<div class="grid grid_items">
					@foreach ($posts as $post)
						<div class="grid_item">
							<a href="{{ route('blog.single', $post->slug) }}" title="{{ $post->title }}">
								<div class="item_img" style="background-image: url({{ Storage::url($post->image) }});"></div>
							</a>
							<div class="item_info">
								<a href="{{ route('blog.single', $post->slug) }}" title="{{ $post->title }}" class="post_title">{{ $post->title }}</a>
								<div class="item_short">{!! $post->short !!}</div>
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