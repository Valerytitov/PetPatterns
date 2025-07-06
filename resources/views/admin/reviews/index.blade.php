@extends('layouts.admin.inside')

@section('title', 'Список отзывов - Отзывы')

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Список отзывов</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('admin.reviews.form') }}" class="btn btn-primary">Добавить</a> 
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
										<th>Автор</th>
										<th width="25%">Текст</th>
										<th>Дата добавления</th>
										<th style="text-align: right;">Действия</th>
									</tr>
								</thead>
								<tbody>
									@if ($list->count())
										@foreach ($list as $rec)
											<tr>
												<td>{{ $rec->id }}</td>
												<td>
													<div class="thumb" style="background-image: url({{ Storage::url($rec->image) }});"></div>
												</td>
												<td>{{ $rec->author }}</td>
												<td width="25%"><p class="lines3">{{ $rec->content }}</p></td>
												<td>{{ date('d.m.Y - H:i', strtotime($rec->created_at)) }}</td>
												<td width="25%" style="text-align: right;">
													<a href="{{ route('admin.reviews.form', $rec->id) }}" class="badge badge-info">
														<i class="fa fa-pen"></i>
														Редактировать
													</a>
													<a href="{{ route('admin.delete', ['review', $rec->id]) }}" class="badge badge-danger">
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