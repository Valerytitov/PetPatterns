@extends('layouts.admin.inside')

@section('title', $title)

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ $title }}</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('admin.posts') }}" class="btn btn-primary">Вернуться к списку</a>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					@if ($errors->any())							
						@foreach ($errors->all() as $err)
							<div class="alert alert-warning">{{ $err }}</div>
						@endforeach
					@endif
					<div class="card">
						<form action="{{ route('admin.posts.form', $id) }}" method="POST" enctype="multipart/form-data">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="slug">URL: <span class="req">*</span></label>
									<input id="slug" type="text" name="slug" value="{{ old('slug', $rec->slug) }}" class="form-control" />
								</div>
								<div class="form-group">
									<label for="title">Заголовок: <span class="req">*</span></label>
									<input id="title" type="text" name="title" value="{{ old('title', $rec->title) }}" class="form-control" />
								</div>
								<div class="form-group">
									<label for="short">Краткое содержание: <span class="req">*</span></label>
									<textarea id="short" name="short" class="form-control">{{ old('short', $rec->short) }}</textarea>
								</div>
								<div class="form-group">
									<label for="content">Содержание записи: <span class="req">*</span></label>
									<textarea id="content" name="content" class="form-control" style="height: 450px;">{{ old('content', $rec->content) }}</textarea>
								</div>
								<div class="form-group">
									<label for="image">Изображение записи:</label>
									<input id="image" type="file" name="image" class="form-control" />
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Сохранить</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection