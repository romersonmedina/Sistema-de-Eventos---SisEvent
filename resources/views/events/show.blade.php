@extends('layouts.main')

@section('title', $event->title)

@section('content')

<div class="col-md-10 offset-md1">
    <div class="row">
        <div id="image-container" class="col-md-6">
            <img src="/img/events/{{ $event->image }}" class="img-fluid" alt="{{ $event->title }}">
        </div>
        <div id="info-container" class="col-md-6">
            <h1>{{ $event->title }}</h1>
            <p class="event-city"><ion-icon name="location-outline"></ion-icon> {{ $event->city }}</p>
            <p class="events-participants"><ion-icon name="people-outline"></ion-icon> {{ count($event->users)}} Participantes</p>
            <p class="event-owner"><ion-icon name="star-outline"></ion-icon> {{ $eventOwner ['name'] }} </p>
            @if(!$hasUserJoined)
                <form action="/events/join/{{ $event->id }}" method="POST">
                    @csrf
                    <a href="/events/join/{{ $event->id }}" class="btn btn-primary" id="event-submit" onclick="event.preventDefault();
                        this.closest('form').submit();">
                            Confirmar presença
                    </a>
                </form>
            @else
                <p class="already-joined-msg">Você já está participando deste evento</p>
            @endif

            <a href="{{ route('event.googleCalendar', $event->id) }}" target="_blank" class="btn btn-primary">
                Adicionar ao Google Calendar
            </a>

            <!-- Botões de compartilhamento -->
            <div id="share-buttons" class="mt-3">
                <h3>Compartilhe este evento:</h3>
                <a href="https://www.instagram.com/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn">
                    <ion-icon name="logo-instagram"></ion-icon>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn">
                    <ion-icon name="logo-twitter"></ion-icon>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($event->title) }}" target="_blank" class="btn">
                    <ion-icon name="logo-linkedin"></ion-icon>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn">
                    <ion-icon name="logo-facebook"></ion-icon>
                </a>
            </div>

            @auth
            <h3 class="mb-4">Deixe seu Feedback</h3>
            <form action="{{ route('feedback.store', $event->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="rating" class="form-label">Nota (1 a 5):</label>
                    <select name="rating" class="form-select" required>
                        <option value="1">1 - Péssimo</option>
                        <option value="2">2 - Ruim</option>
                        <option value="3">3 - Médio</option>
                        <option value="4">4 - Bom</option>
                        <option value="5">5 - Excelente</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Comentário (opcional):</label>
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Enviar Feedback</button>
            </form>
            @endauth


            <h3>O evento conta com:</h3>
            <ul id="items-list">
                @foreach($event->items as $item)
                <li><ion-icon name="play-outline"></ion-icon> <span>{{ $item }}</span></li>
                @endforeach
        </div>
        <div class="col-md-12" id="description-container">
            <h3>Sobre o evento:</h3>
            <p class="event-description">{!! $event->description !!}</p>
        </div>
</div>

@endsection
