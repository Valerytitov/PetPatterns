<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>@yield('title')</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="/admin/plugins/jqvmap/jqvmap.min.css">
	<link rel="stylesheet" href="/admin/css/adminlte.min.css">
	<link rel="stylesheet" href="/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="/admin/plugins/summernote/summernote-bs4.min.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="{{ route('admin') }}" class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="{{ route('admin') }}" class="nav-link">Главная</a>
				</li>
			</ul>
		</nav>
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<a href="{{ route('admin') }}" class="brand-link text-center">
				<span class="brand-text font-weight-bold">Администратор</span>
			</a>
			<div class="sidebar">
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<li class="nav-item">
							<a href="{{ route('admin') }}" class="nav-link @if (Route::current()->getName() == 'admin') active @endif">
								<i class="nav-icon fas fa-tachometer-alt"></i>
								<p>
									Главная
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin.orders') }}" class="nav-link @if (Route::current()->getName() == 'admin.orders') active @endif">
								<i class="nav-icon fas fa-list"></i>
								<p>
									Заказы
								</p>
							</a>
						</li>
						<li class="nav-item @if (Route::current()->getName() == 'admin.vfiles' or Route::current()->getName() == 'admin.vfiles.form' or Route::current()->getName() == 'admin.vfiles.props') menu-open @endif">
							<a href="{{ route('admin.vfiles') }}" class="nav-link @if (Route::current()->getName() == 'admin.vfiles' or Route::current()->getName() == 'admin.vfiles.form' or Route::current()->getName() == 'admin.vfiles.props') active @endif">
								<i class="nav-icon fas fa-route"></i>
								<p>
									Выкройки
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="{{ route('admin.vfiles') }}" class="nav-link @if (Route::current()->getName() == 'admin.vfiles') active @endif">
										<i class="far fa-circle nav-icon"></i>
										<p>Список</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="{{ route('admin.vfiles.form', 0) }}" class="nav-link @if (Route::current()->getName() == 'admin.vfiles.form') active @endif">
										<i class="far fa-circle nav-icon"></i>
										<p>Добавить</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item @if (Route::current()->getName() == 'admin.posts' or Route::current()->getName() == 'admin.posts.form') menu-open @endif">
							<a href="{{ route('admin.posts') }}" class="nav-link @if (Route::current()->getName() == 'admin.posts' or Route::current()->getName() == 'admin.posts.form') active @endif">
								<i class="nav-icon fas fa-flag"></i>
								<p>
									Блог
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="{{ route('admin.posts') }}" class="nav-link @if (Route::current()->getName() == 'admin.posts') active @endif">
										<i class="far fa-circle nav-icon"></i>
										<p>Список записей</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="{{ route('admin.posts.form') }}" class="nav-link @if (Route::current()->getName() == 'admin.posts.form') active @endif">
										<i class="far fa-circle nav-icon"></i>
										<p>Добавить запись</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item @if (Route::current()->getName() == 'admin.reviews' or Route::current()->getName() == 'admin.reviews.form') menu-open @endif">
							<a href="{{ route('admin.reviews') }}" class="nav-link @if (Route::current()->getName() == 'admin.reviews' or Route::current()->getName() == 'admin.reviews.form') active @endif">
								<i class="nav-icon fas fa-comment"></i>
								<p>
									Отзывы
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="{{ route('admin.reviews') }}" class="nav-link @if (Route::current()->getName() == 'admin.reviews') active @endif">
										<i class="far fa-circle nav-icon"></i>
										<p>Список</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="{{ route('admin.reviews.form') }}" class="nav-link @if (Route::current()->getName() == 'admin.reviews.form') active @endif">
										<i class="far fa-circle nav-icon"></i>
										<p>Добавить отзыв</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin.contacts.edit') }}" class="nav-link @if (Route::current()->getName() == 'admin.contacts.edit') active @endif">
								<i class="nav-icon fas fa-address-book"></i>
								<p>Контакты</p>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</aside>
		<div class="content-wrapper">
			@yield('content')
		</div>
	</div>
	<script src="/admin/plugins/jquery/jquery.min.js"></script>
	<script src="/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/admin/plugins/chart.js/Chart.min.js"></script>
	<script src="/admin/plugins/sparklines/sparkline.js"></script>
	<script src="/admin/plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="/admin/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<script src="/admin/plugins/jquery-knob/jquery.knob.min.js"></script>
	<script src="/admin/plugins/moment/moment.min.js"></script>
	<script src="/admin/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<script src="/admin/plugins/summernote/summernote-bs4.min.js"></script>
	<script src="/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<script src="/admin/js/adminlte.js"></script>
	@stack('scripts')
	<script>
		jQuery(function($) {
			$(document).ready(function() {

				$('textarea[name=content]').summernote({
					height: 450
				});

			});
		});
	</script>
</body>
</html>
