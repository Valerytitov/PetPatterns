@extends('layouts.front.layout')

@section('content')
	<section id="about" class="about">
		<div class="flex cols_2">
			<div class="col">
				<h1>Галина Костюк</h1>
				<div class="after_h1">Автор и куратор курса</div>
				<div class="block_content">
					<p>
						Приветствую! Меня зовут Галина Костюк, я — основатель школы "Бери и шей".
						<br />
						Мои курсы онлайн — это внимание к каждому ученику, общение в кругу людей, увлеченных шитьём, возможность с головой погрузиться в швейный мир.
						<br /><br />
						Теперь качественное обучение шитью доступно вам вне зависимости от того, где вы живёте и сколько у вас свободного времени.
						<br /><br />
						Я уверена — вы достигнете желаемых результатов и будете довольны, а главное — вдохновитесь процессом обучения.
						<br />
						Творите, а я буду с удовольствием и гордостью наблюдать за вашими успехами!
					</p>
				</div>
			</div>
			<div class="col">
				<div class="main_img">
					<img src="/uploads/galina.webp" />
				</div>
			</div>
		</div>
	</section>
	<section id="gallery" class="gallery">
		<div class="block_content">
		<h2>Работы наших учеников</h2>
		<div class="grid grid_3 grid_gallery">
			<div class="grid_item" style="background-image: url(/uploads/1.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/2.png);"></div>
			<div class="grid_item" style="background-image: url(/uploads/3.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/1.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/2.png);"></div>
			<div class="grid_item" style="background-image: url(/uploads/3.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/1.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/2.png);"></div>
			<div class="grid_item" style="background-image: url(/uploads/3.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/1.jpg);"></div>
			<div class="grid_item" style="background-image: url(/uploads/2.png);"></div>
			<div class="grid_item" style="background-image: url(/uploads/3.jpg);"></div>
		</div>
		</div>
	</section>
@endsection