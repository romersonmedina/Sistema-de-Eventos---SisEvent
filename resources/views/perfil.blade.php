@extends('layouts.main')

@section('title', 'Meus Eventos')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/inputmask/dist/inputmask.min.js"></script>

<div class="col-md-6 offset-md-3" style= "margin-right: auto;">
    <br>
    <h2>Perfil de {{ $user->name }}</h2>

        <!-- Mensagem de sucesso -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulário de edição -->
        <form action="{{ route('perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="dob">Data de Nascimento</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ $user->birth_date }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Telefone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" required>
            </div>

            <script>
                // Inicializando a máscara para o campo de telefone
                const phoneInput = document.getElementById('phone');
                const im = new Inputmask('(99) 99999-9999');
                im.mask(phoneInput);
            </script>

            <div class="form-group">
                <label for="gender">Gênero</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="masculino" {{ $user->gender == 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="feminino" {{ $user->gender == 'feminino' ? 'selected' : '' }}>Feminino</option>
                    <option value="prefiro não dizer" {{ $user->gender == 'prefiro não dizer' ? 'selected' : '' }}>Prefiro não dizer</option>
                    <option value="outro" {{ $user->gender == 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
        </form>

        <hr>

        <!-- Formulário separado para alteração de senha -->
        <form action="{{ route('perfil.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="new_password">Nova Senha</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <button type="submit" class="btn btn-success">Salvar Nova Senha</button>
        </form>
</div>
@endsection
