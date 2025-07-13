@extends('layouts.front.layout')

@section('content')
<section class="receipt">
    <div class="payment payment_processing">
        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom:24px;">
            <circle cx="12" cy="12" r="10" stroke="#a98aff" stroke-width="4" fill="none" opacity="0.2"/>
            <circle cx="12" cy="12" r="10" stroke="#a98aff" stroke-width="4" fill="none" stroke-dasharray="60 40" stroke-linecap="round">
                <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
            </circle>
        </svg>
        <h1>Генерируем PDF...</h1>
        <p>Ваш заказ оформлен!<br>Выкройка сейчас будет подготовлена.<br>Пожалуйста, не закрывайте страницу.</p>
        <div style="margin-top:32px; color:#a98aff; font-size:16px;">Это обычно занимает 1-2 минуты.</div>
        <div id="progress-bar" style="margin:32px auto 0 auto; width:320px; max-width:90vw; height:12px; background:#f3eaff; border-radius:6px; overflow:hidden; box-shadow:0 1px 4px rgba(169,138,255,0.08);">
            <div id="progress-inner" style="height:100%; width:0; background:#a98aff; transition:width 0.3s;"></div>
        </div>
    </div>
</section>
<script>
    let progress = 0;
    let interval = setInterval(function() {
        progress += 100/90; // 90 шагов за 90 секунд
        if(progress > 100) progress = 100;
        document.getElementById('progress-inner').style.width = progress + '%';
        if(progress >= 100) clearInterval(interval);
    }, 1000);
    setTimeout(function() {
        window.location.href = "{{ route('payment.free.success', ['order' => $order->id]) }}";
    }, 90000);
</script>
@endsection 