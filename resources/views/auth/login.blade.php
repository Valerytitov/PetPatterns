@extends('layouts.admin.outside')

@section('title', 'Авторизация')

@section('content')
<div class="login-box-body">
	<p class="login-box-msg">Авторизуйтесь для продолжения</p>

	<form action="{{ route('login') }}" method="post">
		@csrf
		<div class="form-group has-feedback">
			<input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="form-control" />
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			@error('email')
				<br />
				<div class="alert alert-warning">Укажите E-mail</div>
			@enderror
		</div>
		<div class="form-group has-feedback">
			<input type="password" name="password" placeholder="Password" class="form-control" />
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			@error('password')
				<br />
				<div class="alert alert-warning">Укажите пароль</div>
			@enderror
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
		</div>
	</form>
</div>
@endsection
