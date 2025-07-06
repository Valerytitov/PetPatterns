@extends('layouts.admin.inside')

@section('content')
<div class="container mt-4">
    <h2>Контактные данные</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.contacts.update') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $contact->email) }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}">
        </div>
        <div class="mb-3">
            <label for="telegram" class="form-label">Telegram</label>
            <input type="text" class="form-control" id="telegram" name="telegram" value="{{ old('telegram', $contact->telegram) }}">
        </div>
        <div class="mb-3">
            <label for="vk" class="form-label">VK</label>
            <input type="text" class="form-control" id="vk" name="vk" value="{{ old('vk', $contact->vk) }}">
        </div>
        <div class="mb-3">
            <label for="instagram" class="form-label">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram', $contact->instagram) }}">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
@endsection 