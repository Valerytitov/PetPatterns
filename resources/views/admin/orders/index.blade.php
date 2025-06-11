@extends('layouts.admin.inside')

@section('title', 'Заказы')

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Заказы</h1>
				</div>
				<div class="col-sm-6 text-right">

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
										<th>Покупатель</th>
										<th>Выкройка</th>
										<th>Сумма</th>
										<th>Статус</th>
										<th>Дата оформления</th>
										<th style="text-align: right;">Действия</th>
									</tr>
								</thead>
								<tbody>
									@if ($list->count())
										@foreach ($list as $rec)
											<tr>
												<td>{{ $rec->id }}</td>
												<td>&mdash;</td>
												<td>{{ $rec->vfile->title }}</td>
												<td>{{ number_format($rec->vfile->price, 2, '.', '') }} руб.</td>
												<td>
													@if ($rec->status == 'new')
														<span class="badge badge-success">Новый</span>
													@endif
												</td>
												<td>{{ date('d.m.Y - H:i', strtotime($rec->created_at)) }}</td>
												<td style="text-align: right;">
													<a href="{{ route('admin.posts.form', $rec->id) }}" class="badge badge-info">
														<i class="fa fa-pen"></i>
														Редактировать
													</a>
													<a href="{{ route('admin.delete', ['posts', $rec->id]) }}" class="badge badge-danger">
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