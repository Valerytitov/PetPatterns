<!DOCTYPE HTML>
<html lang="ru-RU">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Бери и Шей</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<link rel="icon" type="image/svg+xml" href="/PetPatterns-photo/logo.svg">
	<link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
	<link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
	<link rel="manifest" href="/manifest.json">
</head>
<body>
	<header class="header">
		<div class="container flex flex_column align_center justify_between">
			<div class="logo_container">
				<a href="{{ route('shop') }}">
					<svg width="368" height="370" viewBox="0 0 368 370" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M184 -0.00012207C285.62 -3.91562e-05 368 82.8273 368 185C368 287.173 285.62 370 184 370C82.3797 370 0.00012207 287.173 0.00012207 185C0.000185636 82.8273 82.3798 -0.00012207 184 -0.00012207ZM264 222.5C259 220.833 246.1 217.8 234.5 219C232.667 234.833 229.2 272.6 230 297C232.667 298 239.6 299.9 246 299.5C249 297.166 257.4 289.6 267 278C265.833 281.667 263.3 291.6 262.5 302C265.334 303 273.2 305.4 282 307C287.167 298.333 300.1 271.9 310.5 235.5C306.5 234 295.7 230.6 284.5 229C280.5 232.833 269.7 243.6 258.5 256C259.833 250.166 262.8 235.3 264 222.5ZM179 212.5C176.667 227 173 263.1 177 291.5C183.167 292.833 199.7 295.7 216.5 296.5C217 295.5 218 291.699 218 284.5C212.334 283.833 200.701 282.3 199.5 281.5C198.3 280.7 199 271.167 199.5 266.5C203 266.833 211.9 267.9 219.5 269.5C220.5 267 222.2 260.9 221 256.5C216 256.167 205 255.3 201 254.5C200.833 252.5 200.9 247 202.5 241C203.501 240.833 209.101 241 223.5 243C225.167 239.5 228.4 229.6 228 218C222 216.167 203.8 212.5 179 212.5ZM75.0001 196.5C72.0001 211.5 66.9001 247.5 70.5001 271.5C82.8335 277.167 113.6 288.6 138 289C144.167 281 159.2 256.3 170 221.5C166.833 219.667 157.8 216 147 216C141.334 229.5 129.9 258 129.5 264C126.5 263.667 119.8 262.5 117 260.5C120.333 250.333 127.5 226.2 129.5 211C127.333 210 121.8 207.9 117 207.5C114.5 214.333 108.4 234.1 104 258.5C101.667 258 95.6001 256.4 90.0001 254C91.8335 247.833 96.0001 228.7 98.0001 201.5C93.6668 199.833 83.0001 196.5 75.0001 196.5ZM294 196.5C293.333 194 289.7 189.3 280.5 190.5C271.3 191.7 269 200.333 269 204.5C270 210 274.8 220 286 216C297.2 212 296 201.333 294 196.5ZM231 177.5C229.667 176.833 226.2 175.7 223 176.5C222 180.5 220.3 191.7 221.5 204.5C222.833 204.833 225.9 205.4 227.5 205C229.1 204.6 233.834 199.833 236 197.5C235.833 200 235.6 205.3 236 206.5C236.4 207.7 239.5 207.667 241 207.5C243.167 203.833 247.6 193.1 248 179.5C247 179 244 178 240 178C238.333 179.667 234.2 184.2 231 189C231.167 186.666 231.4 181.1 231 177.5ZM312.5 103.5C307.167 102.667 295.3 101.5 290.5 103.5C286.667 109.5 277.8 124.9 273 138.5C272.167 132.666 269.7 118.4 266.5 108C261.5 107.667 249.5 107.6 241.5 110C242.833 122.833 247.5 156.1 255.5 186.5C258.167 187.333 264.9 188.5 270.5 186.5C273.834 182.666 282.5 171.8 290.5 159C290.833 165.167 292.1 178.8 294.5 184C298 184.667 306.1 185.6 310.5 184C316 181.999 317.5 116 312.5 103.5ZM99.4767 103C82.9077 103.377 60.6696 106.427 49.8409 108.182L47.8322 108.514L47.0236 108.651L47.0001 109.472C46.1954 137.639 55.0342 172.765 59.547 186.806L59.7706 187.5H60.4991C79.3046 187.5 91.6838 184.625 99.0646 179.575C102.777 177.035 105.229 173.94 106.574 170.402C107.832 167.094 108.101 163.458 107.598 159.616L107.487 158.846C106.405 151.916 101.026 148.042 95.0734 146.004C89.5812 144.123 83.3978 143.722 78.9991 143.937V136.411L99.5919 134.496L100.499 134.411V102.976L99.4767 103ZM228.716 110.302C220.2 101.578 208.52 100.17 197.574 101.837C186.627 103.504 176.224 108.265 169.972 112.151L169.37 112.525L169.524 113.218C176.74 145.692 188.231 175.854 193.085 186.902L193.347 187.5H194C204.661 187.5 211.22 183.062 213.264 180.646L213.603 180.246L213.466 179.739L208.883 162.783C213.13 163.343 217.54 161.937 221.522 159.295C226.022 156.308 230.068 151.687 232.887 146.274C238.434 135.621 239.327 121.644 229.206 110.815L228.716 110.302ZM164.5 105.5C156.833 104.333 135.9 102.7 113.5 105.5C113 120.167 113.8 156.5 121 184.5C128 185.5 146 186.9 162 184.5C162.667 182.833 163.6 178 162 172H143.5C142.667 169.5 141 163.2 141 158H162C162.667 154.333 163.6 146.4 162 144H141C140.167 142 139 136.7 141 131.5H162C162.833 128 164.5 117.9 164.5 105.5ZM78.7062 160.044C79.9176 159.671 81.8316 159.417 83.7023 159.879C85.6246 160.353 87.5075 161.595 88.4396 164.158L88.5255 164.413C88.9176 165.679 88.7682 166.845 88.2169 167.857C87.6512 168.896 86.7035 169.701 85.6788 170.318C83.8887 171.397 81.6198 172.051 79.8615 172.372L79.1417 172.49L78.0001 172.653V160.262L78.7062 160.044ZM208.872 130.089C211.544 130.214 214.296 131.357 215.895 134.554L216.039 134.859C216.715 136.382 216.715 137.822 216.174 139.116C215.616 140.45 214.531 141.519 213.304 142.362C210.854 144.044 207.5 145.054 205.182 145.483L204.221 145.662L204.022 144.704L201.522 132.704L201.364 131.95L202.053 131.606L202.365 131.455C203.983 130.702 206.418 129.974 208.872 130.089ZM199 75.4999C197 58.7001 183.167 55.5 176.5 55.9999C156.5 55.9999 152.5 68.9998 153 75.4999C152.833 82.3332 154.9 95.3999 164.5 92.9999C174.9 86.2001 184.833 90.1666 188.5 92.9999C192 94.1665 199 92.2997 199 75.4999ZM146.088 41.538C140.516 35.1659 132.228 33.2981 127.575 37.3661C122.922 41.4342 123.667 49.898 129.238 56.2704C134.81 62.6428 143.099 64.5114 147.752 60.4432C152.405 56.3751 151.66 47.9104 146.088 41.538ZM223.098 36.6366C218.58 32.4192 210.235 34.0165 204.459 40.204C198.683 46.3916 197.663 54.8263 202.181 59.0438C206.699 63.2611 215.043 61.6639 220.819 55.4764C226.596 49.2889 227.616 40.8541 223.098 36.6366ZM172.144 28.6688C171.078 20.0151 165.241 13.6118 159.107 14.3671C152.973 15.1226 148.863 22.7506 149.929 31.4042C150.994 40.0577 156.831 46.4609 162.965 45.7059C169.099 44.9507 173.209 37.3226 172.144 28.6688ZM192.293 14.411C186.176 13.5275 180.207 19.807 178.96 28.4364C177.714 37.066 181.662 44.7784 187.779 45.662C193.896 46.5453 199.866 40.2659 201.112 31.6366C202.359 23.0071 198.41 15.2946 192.293 14.411Z" fill="white"/>
					</svg>
				</a>
			</div>
			<!-- Мобильный бургер -->
			<button class="burger" id="burgerMenu" aria-label="Открыть меню">
				<span></span><span></span><span></span>
			</button>
			<nav class="mainnav">
				<ul class="flex">
					<li><a href="{{ route('shop') }}" @if (Route::current()->getName() == 'shop') class="active" @endif>Магазин</a></li>
					<li><a href="{{ route('blog') }}" @if (Route::current()->getName() == 'blog') class="active" @endif>Блог</a></li>
					<li><a href="https://beriisheu.ru/" target="_blank" rel="noopener">Обучение</a></li>
				</ul>
			</nav>
			<!-- Мобильное меню -->
			<div class="mobile_menu" id="mobileMenu">
				<ul>
					<li><a href="{{ route('shop') }}">Магазин</a></li>
					<li><a href="{{ route('blog') }}">Блог</a></li>
					<li><a href="https://beriisheu.ru/" target="_blank" rel="noopener">Обучение</a></li>
				</ul>
			</div>
		</div>
		<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
			<defs>
				<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
			</defs>
			<g class="parallax">
				<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
				<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
				<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
				<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
			</g>
		</svg>
	</header>
	<main>
		<div class="container pt-2">
			@if(session('success'))
				{{-- Удаляю глобальный верхний alert-success, чтобы не дублировать сообщения на страницах конструктора --}}
			@endif
			@if(session('error'))
				{{-- Удаляю глобальный верхний alert-danger, чтобы не дублировать сообщения на страницах конструктора --}}
			@endif
			@yield('content')
		</div>
	</main>

	<footer>
		<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
			<defs>
				<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
			</defs>
			<g class="parallax">
				<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
				<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
				<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
				<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
			</g>
		</svg>
		<div class="container">
			<div class="flex align_center justify_between cols cols_2">
				<div class="col logo">
					<a href="{{ route('shop') }}" title="Магазин">
						<img src="/assets/img/logo.png" />
					</a>
				</div>
				<div class="col menu">
					<nav>
						<ul class="flex justify_right">
							<li><a href="{{ route('shop') }}" @if (Route::current()->getName() == 'shop') class="active" @endif>Магазин</a></li>
							<li><a href="{{ route('blog') }}" @if (Route::current()->getName() == 'blog') class="active" @endif>Блог</a></li>
							<li><a href="https://beriisheu.ru/" target="_blank" rel="noopener">Обучение</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="copy">
				<p>&copy; {{ date('Y') }} "Бери и Шей". Информация, представленная здесь, фотографии, видео, логотипы и торговая марка являются собственностью веб-сайта www.beriisheu.ru
Его воспроизведение, полное или частичное, запрещено без специального разрешения администратора веб-сайта.
﻿Отзывы, представленные на этой странице, отражают мнение их авторов, поэтому мы не несем за них ответственности.</p>
			</div>
		</div>
	</footer>
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
	<script src="/assets/js/app.js"></script>
	<script>
// Мобильное меню бургер
const burger = document.getElementById('burgerMenu');
const mobileMenu = document.getElementById('mobileMenu');
burger.addEventListener('click', function() {
	mobileMenu.classList.toggle('open');
	burger.classList.toggle('open');
});
document.addEventListener('click', function(e) {
	if (!burger.contains(e.target) && !mobileMenu.contains(e.target)) {
		mobileMenu.classList.remove('open');
		burger.classList.remove('open');
	}
});
</script>
</body>
</html>