@php
    use Illuminate\Support\Facades\DB;

@endphp

@extends('layouts.main')

@section('title', 'Meus Eventos')

@section('content')
<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h2>Meus Eventos</h2>
</div>
<div class="col-md-10 offset-md-1 dashboard-events-container">
    @if(session('message'))
        <div class="alert alert-success mt-2">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    @if(count($events) > 0)
        <div class="row">
            @foreach ($events as $event)
                <div class="col-md-3">
                    <div class="card">
                        @if($event->image)
                            <img src="/img/events/{{ $event->image }}" alt="{{ $event->title }}" class="card-img-top">
                        @endif
                        <div class="card-body">
                            <p class="card-date">Data do evento: {{ date('d/m/Y', strtotime($event->date)) }}</p>
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text">Participantes: {{ count($event->users) }}</p>
                            <p id="countdown-{{ $event->id }}" class="countdown" data-event-date="{{ date('Y-m-d', strtotime($event->date)) }}"></p>
                            <a href="/events/{{ $event->id }}" class="btn btn-primary">Ver detalhes</a>
                            <a href="/events/{{ $event->id }}/statistics" class="btn btn-primary">Estatísticas</a>
                            <div class="mt-2">
                                <a href="/events/edit/{{ $event->id }}" class="btn btn-info">Editar</a>
                                <a href="/events/{{ $event->id }}/participants" class="btn btn-success">Lista de Inscritos</a>
                                <form action="/events/{{ $event->id }}/toggle-attendance" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn {{ $event->attendance_open ? 'btn-success' : 'btn-primary' }}">
                                        {{ $event->attendance_open ? 'Reenviar Lista' : 'Liberar Lista' }}
                                    </button>
                                </form>
                                <form action="/events/{{ $event->id }}/print-attendance" method="GET" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info">Imprimir Lista Assinada</button>
                                </form>
                                <form action="/events/{{ $event->id }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Deletar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Você ainda não tem eventos, <a href="/events/create">criar evento</a></p>
    @endif
</div>

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h2>Eventos que irei participar</h2>
</div>
<div class="col-md-10 offset-md-1 dashboard-events-container">
    @if(is_countable($eventsAsParticipant) && count($eventsAsParticipant) > 0)
        <div class="row d-flex flex-wrap">
            @foreach($eventsAsParticipant as $event)
                <div class="col-md-3">
                    <div class="card mb-3">
                        @if($event->image)
                            <img src="/img/events/{{ $event->image }}" alt="{{ $event->title }}" class="card-img-top">
                        @endif
                        <div class="card-body">
                            <p class="card-date">Data do evento: {{ date('d/m/Y', strtotime($event->date)) }}</p>
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text">Participantes: {{ count($event->users) }}</p>
                            <a href="/events/{{ $event->id }}" class="btn btn-primary">Ver detalhes</a>
                            <div class="mt-2">
                                <form action="/events/leave/{{ $event->id }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Sair do evento</button>
                                </form>
                                @if($event->attendance_open)
                                    @php
                                        $userAttended = DB::table('event_user')
                                            ->where('event_id', $event->id)
                                            ->where('user_id', auth()->user()->id)
                                            ->value('attended');
                                    @endphp
                                    @if($userAttended)
                                        <button class="btn btn-success">Lista assinada</button>
                                    @else
                                        <form action="/events/{{ $event->id }}/confirm-attendance" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Assinar Lista</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">Lista de presença fechada</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Você ainda não está participando de nenhum evento, <a href="/">ver todos os eventos</a></p>
    @endif
</div>

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h2>Eventos Encerrados</h2>
    <p class="subtitle">Eventos com inscrições encerradas ou que já aconteceram</p>
    <div class="row d-flex flex-wrap"> <!-- Adicionando a row para alinhar os cards -->
        @foreach($pastEvents as $event)
        <div class="col-md-3">
            <div class="card">
                <img src="/img/events/{{ $event->image }}" alt="{{ $event->title }}" class="card-img-top">
                <div class="card-body">
                    <p class="card-date">Data do evento: {{ date('d/m/Y', strtotime($event->date)) }}</p>
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <p class="card-participantes">{{ count($event->users) }} Participantes</p>
                    <p id="countdown-{{ $event->id }}" class="countdown" data-event-date="{{ date('Y-m-d', strtotime($event->date)) }}"></p>

                    <!-- Mantendo os botões ativos mesmo após o evento ser encerrado -->
                    <a href="/events/{{ $event->id }}" class="btn btn-primary">Ver detalhes</a>
                    <a href="/events/{{ $event->id }}/statistics" class="btn btn-primary">Estatísticas</a>
                    <a href="/events/{{ $event->id }}/participants" class="btn btn-success">Lista de Inscritos</a>
                    <form action="/events/{{ $event->id }}/print-attendance" method="GET" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info">Imprimir Lista Assinada</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>



@endsection
