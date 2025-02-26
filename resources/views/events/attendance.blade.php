@extends('layouts.main')

@section('title', 'Lista de Presença - {{ $event->title }}')

@section('content')
<div class="col-md-10 offset-md-1">
    <h2>Lista de Presença: {{ $event->title }}</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <p>Este evento está disponível para assinatura da lista de presença.</p>
    <form action="{{ route('events.signAttendance', $event->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Assinar Lista de Presença</button>
    </form>
</div>
@endsection
