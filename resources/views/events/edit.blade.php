@extends('layouts.main')

@section('title', 'Editando: ' . $event->title)

@section('content')

<div id="event-create-container" class="col-md-6 offset-md-3" style= "margin-right: auto;">
    <h1>Editando: {{ $event->title }}</h1>
    <form action="/events/update/{{ $event->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="image">Imagem do Evento:</label>
            <input type="file" id="image" name="image" class="from-control-file">
            <img src="/img/events/{{ $event->image}}" alt="{{$event->title}}" class="img-preview">
        </div>

        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento" value="{{ $event->title }}">
        </div>

        <div class="form-group">
            <label for="title">Data do Evento:</label>
            <input type="date" class="form-control" id="date" name="date" value="{{date('Y-m-d', strtotime($event->date));}}">
        </div>

        <div class="form-group">
            <label for="title">Cidade:</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Local do evento" value="{{ $event->city }}">
        </div>

        <div class="form-group">
            <label for="title">Evento privado?</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1" {{ $event->private == 1 ? "selected='selected'" : ""}}>Sim</option>
            </select>
        </div>

        <div class="form-group">
            <label for="title">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="O que ocorrerá no evento?">{!! $event->description !!}</textarea>
        </div>

        <div class="form-group">
            <label for="title">Recursos e Serviços do evento:</label>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Certificados de participação"> Certificados de participação</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Palestras"> Palestras</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Workshops"> Workshops</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Painéis de discussão"> Painéis de discussão</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Exposições de projetos"> Exposições de projetos</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Rodadas de networking"> Rodadas de networking</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Aulas práticas"> Aulas práticas</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Material didático (apostilas, livros, etc.)"> Material didático (apostilas, livros, etc.)</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Sistema de tradução simultânea"> Sistema de tradução simultânea</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Coffe break"> Coffe break</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Transmissão online (streaming)"> Transmissão online (streaming)</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Rifa ou sorteios"> Rifa ou sorteios</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Estacionamento"> Estacionamento</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Wifi grátias"> Wifi grátis</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Quiosques de alimentação"> Quiosques de alimentação</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="QEspaço para exposições"> Espaço para exposições</input>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" value="Salvar Edição">
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            tinymce.init({
                selector: '#description', // Aplica ao campo de descrição
                menubar: false, // Oculta a barra de menu superior
                plugins: 'lists link image', // Plugins básicos para formatação
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image',
                height: 300, // Define a altura do editor
                branding: false // Remove a marca do TinyMCE
            });
        });
    </script>
@endsection
