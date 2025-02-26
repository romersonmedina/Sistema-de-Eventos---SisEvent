@php
    use Carbon\Carbon;
@endphp

@extends('layouts.main')

@section('title', 'SisEvents')

@section('content')

<div id="search-container" class="col-md-12">
    <h1>Busque um evento</h1>
    <form action="/" method="GET">
        <input type="text" id="search" name="search" class="form-control" placeholder="Procurar...">
    </form>
</div>

<div id="events-container" class="col-md-12">
    @if($search)
        <h2>Buscando por: {{ $search }}</h2>
    @else
        <h2>Próximos Eventos</h2>
        <p class="subtitle">Veja os eventos dos próximos dias</p>
    @endif

    <div id="cards-container" class="row">
        <!-- Exibição de eventos futuros -->
        @foreach($upcomingEvents as $event)
        <div class="card col-md-3">
            <img src="/img/events/{{ $event->image }}" alt="{{ $event->title }}">
            <div class="card-body">
                <p class="card-date">Data do evento: {{ date('d/m/Y', strtotime($event->date)) }}</p>
                <h5 class="card-title">{{ $event->title }}</h5>
                <p class="card-participantes">{{ count($event->users) }} Participantes</p>
                <p id="countdown-{{ $event->id }}" class="countdown" data-event-date="{{ date('Y-m-d', strtotime($event->date)) }}"></p>
                <!-- Verifica se o evento já ocorreu -->

                @if(\Carbon\Carbon::parse($event->date)->startOfDay()->lessThan(\Carbon\Carbon::today()))
                     <i class="fas fa-hourglass-start"></i><p>Evento encerrado</p>
                @else
                    <a href="/events/{{ $event->id }}" class="btn btn-primary">Saiba mais</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<div id="events-container" class="col-md-12">
    <h2>Eventos Encerrados</h2>
    <div id="cards-container" class="row">
        <p class="subtitle">Eventos com inscrições encerradas ou que já aconteceram</p>
        @foreach($pastEvents as $event)
        <div class="card col-md-3">
            <img src="/img/events/{{ $event->image }}" alt="{{ $event->title }}">
            <div class="card-body">
                <p class="card-date">Data do evento: {{ date('d/m/Y', strtotime($event->date)) }}</p>
                <h5 class="card-title">{{ $event->title }}</h5>
                <p class="card-participantes">{{ count($event->users) }} Participantes</p>
                <p id="countdown-{{ $event->id }}" class="countdown" data-event-date="{{ date('Y-m-d', strtotime($event->date)) }}"></p>
                <!-- Exibe que o evento está encerrado e não mostra o botão -->
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Script countdown.js carregado!");

        function updateCountdown() {
            document.querySelectorAll('.countdown').forEach(function (element) {
                let eventDateStr = element.getAttribute('data-event-date');

                if (!eventDateStr) {
                    element.innerHTML = "Data inválida!";
                    return;
                }

                // Criando a data do evento corretamente no fuso local
                let eventDateParts = eventDateStr.split("-");
                let eventDate = new Date(eventDateParts[0], eventDateParts[1] - 1, eventDateParts[2]);

                // Criando a data de hoje no fuso local
                let today = new Date();
                today.setHours(0, 0, 0, 0);

                let timeLeft = eventDate.getTime() - today.getTime();
                let daysLeft = timeLeft / (1000 * 60 * 60 * 24); // Número exato de dias

                if (daysLeft > 0) {
                    element.innerHTML = `Faltam ${Math.round(daysLeft)} dias`;
                } else if (daysLeft === 0) {
                    element.innerHTML = "O evento ocorre hoje";
                } else {
                    element.innerHTML = "Evento encerrado";
                }
            });
        }

        updateCountdown();
        setInterval(updateCountdown, 60000); // Atualiza a cada 60 segundos
    });
</script>
