@extends('layouts.main')

@section('title', $event->title)

@section('content')

<div class="col-md-10 offset-md1">
    @if(session('message'))
        <div class="alert alert-success mt-2">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif
    <div class="row">
        <div id="image-container" class="col-md-6">
            <img src="/img/events/{{ $event->image }}" class="img-fluid" alt="{{ $event->title }}">
        </div>
        <div id="info-container" class="col-md-6">
            <h1>{{ $event->title }}</h1>
            <p class="event-city"><ion-icon name="location-outline"></ion-icon> {{ $event->city }}</p>
            <p class="events-participants"><ion-icon name="people-outline"></ion-icon> {{ count($event->users) }} Participantes</p>
            <p class="event-owner"><ion-icon name="star-outline"></ion-icon> {{ $eventOwner->name }} </p>

            <!-- Botões modificados para 'manage' -->
            <a href="/events/edit/{{ $event->id }}" class="btn btn-info d-inline">Editar Evento</a>
            <a href="/events/{{ $event->id }}/participants" class="btn btn-success d-inline">Lista de Inscritos</a>
            <form action="{{ route('event.toggle-attendance', $event->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    {{ $event->attendance_open ? 'Reenviar Lista' : 'Liberar Lista de Presença' }}
                </button>
            </form>

            <form action="/events/{{ $event->id }}/print-attendance" method="GET" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info">Imprimir Lista Assinada</button>
            </form>

            <form action="{{ route('event.delete', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Você tem certeza que deseja deletar este evento?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Deletar Evento</button>
            </form>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
            <br>
            <br>
            <h4>O evento conta com:</h4>
            <ul id="items-list">
                @foreach($event->items as $item)
                <li><ion-icon name="play-outline"></ion-icon> <span>{{ $item }}</span></li>
                @endforeach
            </ul>
            <div class="col-md-12" id="description-container">
                <h3>Sobre o evento:</h3>
                <p class="event-description">{!! $event->description !!}</p>
            </div>
    </div>
</div>

@endsection
