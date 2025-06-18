@extends('layouts.admin.inside')

@section('title', 'Список - Выкройки')

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Выкройки</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('admin.vfiles.form', 0) }}" class="btn btn-primary">Добавить</a>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					@if (\Session::has('success'))
						<div class="alert alert-success">{!! \Session::get('success') !!}</div>
					@endif
					@if (\Session::has('error'))
						<div class="alert alert-warning">{!! \Session::get('error') !!}</div>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body table-responsive p-0">
							<table class="table table-hover text-nowrap">
								<thead>
									<tr>
										<th style="width: 50px;">ID</th>
										<th>Изображение</th>
										<th>Название</th>
										<th>Цена</th>
										<th style="text-align: right;">Действия</th>
									</tr>
								</thead>
								<tbody>
									@if ($list->count())
										@foreach ($list as $rec)
											<tr>
												<td>{{ $rec->id }}</td>
												<td>
													<a href="{{ route('admin.vfiles.form', $rec->id) }}">
														<div class="thumb" style="background-image: url({{ Storage::url($rec->image) }});"></div>
														<p class="title">{{ $rec->title }}</p>
													</a>
												</td>
												<td>{{ $rec->title }}</td>
												<td>{{ $rec->price }} руб.</td>
												<td style="text-align: right;">
													<a href="{{ route('admin.vfiles.props', $rec->id) }}" class="badge badge-warning" style="display: none;">
														<i class="fa fa-cog"></i>
														Параметры
													</a>
													<a href="{{ route('admin.vfiles.form', $rec->id) }}" class="badge badge-info">
														<i class="fa fa-pencil"></i>
														Редактировать
													</a>
													<a href="{{ route('admin.delete', ['vfile', $rec->id]) }}" class="badge badge-danger">
														<i class="fa fa-close"></i>
														Удалить
													</a>
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="8">Нет информации</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
