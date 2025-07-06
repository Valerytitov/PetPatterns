@extends('layouts.admin.inside')

@section('title', 'Список - Блог')

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Блог</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('admin.posts.form') }}" class="btn btn-primary">Добавить запись</a>
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
										<th>ID</th>
										<th>Slug</th>
										<th>Заголовок</th>
										<th>Дата</th>
										<th style="text-align: right;">Действия</th>
									</tr>
								</thead>
								<tbody>
									@if ($list->count())
										@foreach ($list as $rec)
											<tr>
												<td>{{ $rec->id }}</td>
												<td>{{ $rec->slug }}</td>
												<td>{{ $rec->title }}</td>
												<td>{{ $rec->created_at }}</td>
												<td style="text-align: right;">
													<a href="{{ route('admin.posts.form', $rec->id) }}" class="badge badge-info">
														<i class="fa fa-pencil"></i> Редактировать
													</a>
													<a href="{{ route('admin.delete', ['post', $rec->id]) }}" class="badge badge-danger" onclick="return confirm('Удалить запись?')">
														<i class="fa fa-close"></i> Удалить
													</a>
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="5">Нет записей</td>
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