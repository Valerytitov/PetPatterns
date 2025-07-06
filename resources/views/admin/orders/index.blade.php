@extends('layouts.admin.inside')

@section('title', 'Список - Заказы')

@section('content')
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Заказы</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{ route('admin.orders') }}" class="btn btn-primary">Обновить</a>
				</div>
			</div>
			<form method="GET" class="row mb-3" action="{{ route('admin.orders') }}">
				<div class="col-md-2">
					<select name="status" class="form-control">
						<option value="">Все статусы</option>
						<option value="pending" @if(request('status')=='pending') selected @endif>Ожидание</option>
						<option value="paid" @if(request('status')=='paid') selected @endif>Оплачен</option>
						<option value="failed" @if(request('status')=='failed') selected @endif>Ошибка</option>
					</select>
				</div>
				<div class="col-md-3">
					<input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}" />
				</div>
				<div class="col-md-2">
					<button class="btn btn-secondary" type="submit">Фильтр</button>
				</div>
			</form>
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
			<form method="POST" action="{{ route('admin.orders.delete_mass') }}" id="mass-delete-form">
				@csrf
				<div class="row mb-2">
					<div class="col-md-12">
						<button type="submit" class="btn btn-danger" onclick="return confirm('Удалить выбранные заказы?')">Удалить выбранные</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body table-responsive p-0">
								<table class="table table-hover text-nowrap">
									<thead>
										<tr>
											<th><input type="checkbox" id="check-all"></th>
											<th>ID</th>
											<th>Email</th>
											<th>Статус</th>
											<th>Сумма</th>
											<th>PDF</th>
											<th>Дата</th>
											<th style="text-align: right;">Действия</th>
										</tr>
									</thead>
									<tbody>
										@if ($list->count())
											@foreach ($list as $rec)
												<tr>
													<td><input type="checkbox" name="ids[]" value="{{ $rec->id }}" class="row-check"></td>
													<td>{{ $rec->id }}</td>
													<td>{{ $rec->email }}</td>
													<td>
														@if ($rec->status == 'pending')
															<span class="badge badge-warning">Ожидание</span>
														@elseif ($rec->status == 'paid')
															<span class="badge badge-success">Оплачен</span>
														@else
															<span class="badge badge-danger">Ошибка</span>
														@endif
													</td>
													<td>{{ number_format($rec->sum, 2, '.', '') }} ₽</td>
													<td>
														@if($rec->pdf_path)
															<a href="{{ asset('storage/' . str_replace('public/', '', $rec->pdf_path)) }}" target="_blank">PDF</a>
														@else
															<span class="text-muted">—</span>
														@endif
													</td>
													<td>{{ $rec->created_at }}</td>
													<td style="text-align: right;">
														<a href="{{ route('admin.orders.form', $rec->id) }}" class="badge badge-info">
															<i class="fa fa-pencil"></i> Редактировать
														</a>
														<a href="{{ route('admin.delete', ['type' => 'order', 'id' => $rec->id]) }}" class="badge badge-danger" onclick="return confirm('Удалить заказ?')">
															<i class="fa fa-trash"></i> Удалить
														</a>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="7">Нет заказов</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
        });
    }
});
</script>
@endpush