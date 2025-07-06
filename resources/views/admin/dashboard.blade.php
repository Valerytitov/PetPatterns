@extends('layouts.admin.inside')

@section('title', 'Панель управления')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Панель управления</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Статистика -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['orders_total'] }}</h3>
                        <p>Всего заказов</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="small-box-footer">
                        Подробнее <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['orders_paid'] }}</h3>
                        <p>Оплаченных заказов</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="small-box-footer">
                        Подробнее <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['orders_pending'] }}</h3>
                        <p>Ожидающих оплаты</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="small-box-footer">
                        Подробнее <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['vfiles_total'] }}</h3>
                        <p>Выкроек</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <a href="{{ route('admin.vfiles') }}" class="small-box-footer">
                        Подробнее <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['posts_total'] }}</h3>
                        <p>Записей в блоге</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flag"></i>
                    </div>
                    <a href="{{ route('admin.posts') }}" class="small-box-footer">
                        Подробнее <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $stats['reviews_total'] }}</h3>
                        <p>Отзывов</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <a href="{{ route('admin.reviews') }}" class="small-box-footer">
                        Подробнее <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Последние заказы -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shopping-cart mr-1"></i>
                            Последние заказы
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>
                                            @if($order->status == 'paid')
                                                <span class="badge badge-success">Оплачен</span>
                                            @else
                                                <span class="badge badge-warning">Ожидает</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->sum }} ₽</td>
                                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Нет заказов</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.orders') }}" class="btn btn-primary btn-sm">Все заказы</a>
                    </div>
                </div>
            </div>

            <!-- Последние выкройки -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-route mr-1"></i>
                            Последние выкройки
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Цена</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_vfiles as $vfile)
                                    <tr>
                                        <td>{{ $vfile->id }}</td>
                                        <td>{{ $vfile->title }}</td>
                                        <td>{{ $vfile->price }} ₽</td>
                                        <td>{{ $vfile->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Нет выкроек</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.vfiles') }}" class="btn btn-primary btn-sm">Все выкройки</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние посты -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-flag mr-1"></i>
                            Последние записи в блоге
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Заголовок</th>
                                    <th>Slug</th>
                                    <th>Дата</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->slug }}</td>
                                        <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.posts.form', $post->id) }}" class="badge badge-info">
                                                <i class="fa fa-pencil"></i> Редактировать
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Нет записей</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.posts') }}" class="btn btn-primary btn-sm">Все записи</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection