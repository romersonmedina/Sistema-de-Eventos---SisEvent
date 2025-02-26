@extends('layouts.main')

@section('Feedbacks para o Evento: {{ $event->title }}')

@section('content')
<div class="container">
    <h2 class="mb-4">Feedbacks para o Evento: {{ $event->title }}</h2>

    @if($feedbacks->isEmpty())
        <div class="alert alert-warning">
            Não há feedbacks para este evento.
        </div>
    @else
        <div class="row">
            @foreach($feedbacks as $feedback)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $feedback->user->name }}</h5>
                            <p class="card-text">
                                <strong>Avaliação:</strong>
                                @if($feedback->rating)
                                    <span class="badge {{ $feedback->rating >= 4 ? 'badge-success' : 'badge-warning' }} text-dark">
                                        {{ $feedback->rating }} / 5
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Nenhuma avaliação</span>
                                @endif
                            </p>
                            <p class="card-text">
                                <strong>Comentário:</strong>
                                <br>
                                <small>{{ $feedback->comment }}</small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Feedback enviado em {{ $feedback->created_at->format('d/m/Y H:i') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
