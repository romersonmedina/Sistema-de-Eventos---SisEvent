<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <a href="/" class="navbar-brand">
                <img src="/img/logo.png" alt="SisEvents">
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e enviaremos um e-mail com um link de redefinição de senha que permitirá que você escolha uma nova.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Redefinir a senha') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
