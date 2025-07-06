@extends('layouts.front.layout')

@section('content')
<section class="contacts">
    <div class="breadcrumbs">
        <ul>
            <li><a href="{{ route('home') }}">Главная</a></li>
            <li class="active">Контакты</li>
        </ul>
    </div>
    <div class="contacts_block">
        <h1>Контакты</h1>
        <div class="contacts_grid">
            <div class="contacts_info">
                @if($contact && $contact->email)
                    <p><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                @endif
                @if($contact && $contact->phone)
                    <p><strong>Телефон:</strong> <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></p>
                @endif
                <p><strong>Соцсети:</strong></p>
                <div class="contacts_socials">
                    @if($contact && $contact->telegram)
                        <a href="https://t.me/{{ ltrim($contact->telegram, '@') }}" target="_blank" rel="noopener" class="social_link">Telegram</a>
                    @endif
                    @if($contact && $contact->vk)
                        <a href="https://vk.com/{{ ltrim($contact->vk, '@') }}" target="_blank" rel="noopener" class="social_link">VK</a>
                    @endif
                    @if($contact && $contact->instagram)
                        <a href="https://instagram.com/{{ ltrim($contact->instagram, '@') }}" target="_blank" rel="noopener" class="social_link">Instagram</a>
                    @endif
                </div>
            </div>
            <div class="contacts_form">
                <form method="post" action="#" class="form">
                    <div class="form_group">
                        <label for="name">Ваше имя</label>
                        <input type="text" id="name" name="name" class="form_control" required />
                    </div>
                    <div class="form_group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form_control" required />
                    </div>
                    <div class="form_group">
                        <label for="message">Сообщение</label>
                        <textarea id="message" name="message" class="form_control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn_primary">Отправить</button>
                </form>
            </div>
        </div>
    </div>
</section>
<style>
.contacts_block { max-width: 900px; margin: 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 2px 16px rgba(41,17,79,0.07); padding: 40px 32px; }
.contacts_grid { display: flex; flex-wrap: wrap; gap: 40px; }
.contacts_info { flex: 1 1 260px; font-size: 18px; }
.contacts_socials { margin-top: 12px; display: flex; gap: 16px; }
.social_link { color: #a98aff; text-decoration: none; font-weight: 500; transition: color .2s; }
.social_link:hover { color: #29114f; }
.contacts_form { flex: 1 1 320px; }
.form_group { margin-bottom: 18px; }
.form_control { width: 100%; border-radius: 12px; border: 1px solid #a98aff; background: #fcfbfe; padding: 10px 16px; font-size: 16px; }
.btn_primary { background: #a98aff; color: #fff; border: none; border-radius: 12px; padding: 10px 28px; font-size: 18px; cursor: pointer; transition: background .2s; }
.btn_primary:hover { background: #29114f; }
@media (max-width: 900px) { .contacts_grid { flex-direction: column; gap: 24px; } .contacts_block { padding: 24px 8px; } }
</style>
@endsection 