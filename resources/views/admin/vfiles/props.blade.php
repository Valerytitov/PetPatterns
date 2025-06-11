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
					<a href="{{ route('admin.vfiles') }}" class="btn btn-primary">Вернуться к списку</a>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container-fluid">
		
		</div>
	</section>
@endsection