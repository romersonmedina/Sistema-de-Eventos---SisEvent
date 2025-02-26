@extends('layouts.main')

@section('title', 'Criar Evento')

@section('content')

<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Crie o seu evento</h1>
    <form id="event-form" action="/events" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Banner do Evento:</label>
            <input type="file" id="image" name="image" class="from-control-file">
            <span class="error-message" id="image-error" style="color: red; display: none;">
                ⚠️ A imagem é obrigatória!
            </span>
        </div>

        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento">
            <span class="error-message" id="title-error" style="color: red; display: none;">
                ⚠️ O título é obrigatório!
            </span>
        </div>

        <div class="form-group">
            <label for="event-date">Data do Evento:</label>
            <input type="date" class="form-control" id="event-date" name="date">
            <span class="error-message" id="event-date-error" style="color: red; display: none;">
                ⚠️ A data é obrigatória!
            </span>
        </div>

        <div class="form-group">
            <label for="event-city">Cidade:</label>
            <input type="text" class="form-control" id="event-city" name="city" placeholder="Local do evento">
            <span class="error-message" id="event-city-error" style="color: red; display: none;">
                ⚠️ A cidade é obrigatória!
            </span>
        </div>

        <div class="form-group">
            <label for="private">Evento privado?</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" class="form-control" placeholder="O que ocorrerá no evento?"></textarea>
            <span class="error-message" id="description-error" style="color: red; display: none;">
                ⚠️ A descrição é obrigatória!
            </span>
        </div>

        <div class="form-group">
            <label for="event-items">Recursos e Serviços do evento:</label>
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
                <input type="checkbox" name="items[]" value="Wifi grátis"> Wifi grátis</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Quiosques de alimentação"> Quiosques de alimentação</input>
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" value="Espaço para exposições"> Espaço para exposições</input>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" value="Criar Evento">
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


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const form = document.getElementById("event-form");
            const requiredFields = ["image", "title", "event-date", "event-city", "description"];

            form.addEventListener("submit", function (event) {
                let isValid = true;

                requiredFields.forEach(fieldId => {
                    let field = document.getElementById(fieldId);
                    let errorMessage = document.getElementById(fieldId + "-error");

                    if (!field || !errorMessage) return; // Evita erro se o elemento não existir

                    if (field.value.trim() === "") {
                        event.preventDefault(); // Impede o envio do formulário
                        errorMessage.style.display = "block"; // Mostra a mensagem de erro
                        field.classList.add("is-invalid"); // Adiciona borda vermelha (se quiser estilizar no CSS)
                        isValid = false;
                    } else {
                        errorMessage.style.display = "none";
                        field.classList.remove("is-invalid");
                    }
                });

                return isValid;
            });

            // Esconde a mensagem de erro quando o usuário começa a digitar/preencher
            requiredFields.forEach(fieldId => {
                let field = document.getElementById(fieldId);
                let errorMessage = document.getElementById(fieldId + "-error");

                if (!field || !errorMessage) return;

                field.addEventListener("input", function () {
                    if (field.value.trim() !== "") {
                        errorMessage.style.display = "none";
                        field.classList.remove("is-invalid");
                    }
                });
            });
        });
    </script>

@endsection
