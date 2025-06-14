@extends('layouts.front.layout')

@section('content')
	<section class="construct">
		<h1>Создать выкройку</h1>
		<div class="steps">
			<ul>
				<li>
					<div><span>1</div></span>
					<p>Выберите тип нужного изделия или выберите из <a href="{{ route('shop') }}">каталога</a></p>
				</li>
				<li>
					<div><span>2</div></span>
					<p>Укажите собственные размеры, следуя инструкции</p>
				</li>
				<li>
					<div><span>3</div></span>
					<p>Получите готовую для печати выкройку в формате А4!</p>
				</li>
			</ul>
		</div>
		<div class="desk">
			<div class="preview">
				<div class="inside"></div>
			</div>
			<div class="panel">
				<form id="construct" action="{{ route('vfiles.generate', ['vfile' => 1]) }}" method="POST">
					@csrf
					<div class="ctitle">
						<h2>Изделие: <span>Майка</span></h2>
						<a href="#" class="selectVType">Выбрать тип изделия</a>
					</div>
					<div class="cgroup">
						<div class="form_group">
							<label>Длина передних лап</label>
							<input type="text" name="measurements[d1]" value="7" />
						</div>
						<div class="form_group">
							<label>Длина задних лап</label>
							<input type="text" name="measurements[d2]" value="10" />
						</div>
						<div class="form_group">
							<label>Длина спинки</label>
							<input type="text" name="measurements[d3]" value="25" />
						</div>
					</div>
					<div class="cgroup">
						<div class="form_group">
							<label>Обхват груди</label>
							<input type="text" name="measurements[o1]" value="34" />
						</div>
						<div class="form_group">
							<label>Обхват шеи</label>
							<input type="text" name="measurements[o2]" value="24" />
						</div>
						<div class="form_group">
							<label>Расстояние между лапами</label>
							<input type="text" name="measurements[r1]" value="4" />
						</div>
					</div>
					<div class="btns">
						<button type="submit" class="btn btn_primary">Построить выкройку</button>
						<a href="#" class="btn btn_gray">Очистить</a>
					</div>
				</form>
			</div>
		</div>
	</section>
@endsection