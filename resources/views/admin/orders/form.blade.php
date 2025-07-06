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
                <a href="{{ route('admin.orders') }}" class="btn btn-primary">Вернуться к списку</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <form method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>ID заказа:</label>
                                <input type="text" class="form-control" value="{{ $rec->id }}" disabled />
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $rec->email) }}" required />
                            </div>
                            <div class="form-group">
                                <label>Статус:</label>
                                <select name="status" class="form-control">
                                    <option value="pending" @if($rec->status=='pending') selected @endif>Ожидание оплаты</option>
                                    <option value="paid" @if($rec->status=='paid') selected @endif>Оплачен</option>
                                    <option value="failed" @if($rec->status=='failed') selected @endif>Ошибка</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Сумма:</label>
                                <input type="text" class="form-control" value="{{ $rec->sum }}" disabled />
                            </div>
                            <div class="form-group">
                                <label>PDF:</label>
                                @if($rec->pdf_path)
                                    <a href="{{ asset('storage/' . str_replace('public/', '', $rec->pdf_path)) }}" target="_blank">Скачать PDF</a>
                                @else
                                    <span class="text-muted">Не сгенерирован</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Дата создания:</label>
                                <input type="text" class="form-control" value="{{ $rec->created_at }}" disabled />
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection 