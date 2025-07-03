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
					<a href="{{ route('admin.reviews') }}" class="btn btn-primary">Вернуться к списку</a>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
			<form action="{{ $id == 0 ? route('admin.reviews.form.create') : route('admin.reviews.form.edit', $id) }}" method="POST" enctype="multipart/form-data" class="row">
				@csrf
				<div class="col-md-12">
					@if ($errors->any())							
						@foreach ($errors->all() as $err)
							<div class="alert alert-warning">{{ $err }}</div>
						@endforeach
					@endif
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="title">Заголовок: <span class="req">*</span></label>
										<input id="title" type="text" name="title" value="{{ old('title', $rec->title) }}" class="form-control" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="author">Автор отзыва: <span class="req">*</span></label>
										<input id="author" type="text" name="author" value="{{ old('author', $rec->author) }}" class="form-control" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="form-group">
								<label for="content">Содержание отзыва: <span class="req">*</span></label>
								<textarea id="content" name="content1" class="form-control" style="height: 250px;">{{ old('content', $rec->content) }}</textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="form-group">
								<label for="image">Изображение автора:</label>
								<input id="image" type="file" name="image" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-footer">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
@endsection