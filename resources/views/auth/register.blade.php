<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask/dist/inputmask.min.js"></script>


    <x-authentication-card>
        <x-slot name="logo">
            <a href="/" class="navbar-brand">
                <img src="/img/logo.png" alt="SisEvents">
            </a>
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nome') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" placeholder="Digite seu nome completo" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" placeholder="Digite um email válido" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="phone" value="{{ __('Telefone') }}" />
                <x-input id="phone" class="block mt-1 w-full" type="text" placeholder="Digite seu telefone" name="phone" :value="old('phone')" required />
            </div>

            <script>
                // Inicializando a máscara para o campo de telefone
                const phoneInput = document.getElementById('phone');
                const im = new Inputmask('(99) 99999-9999');
                im.mask(phoneInput);
            </script>

            <div class="mt-4">
                <x-label for="birth_date" value="{{ __('Data de Nascimento') }}" />
                <x-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" required />
            </div>

            <div class="mt-4">
                <x-label for="gender" value="{{ __('Gênero') }}" />
                <select id="gender" name="gender" class="block mt-1 w-full" required>
                    <option value="" disabled selected>Selecione seu gênero</option>
                    <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="feminino" {{ old('gender') == 'feminino' ? 'selected' : '' }}>Feminino</option>
                    <option value="feminino" {{ old('gender') == 'prefiro não dizer' ? 'selected' : '' }}>Prefiro não dizer</option>
                    <option value="outro" {{ old('gender') == 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Senha') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" placeholder="Digite uma senha" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirme a senha') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" placeholder="Confirme sua senha" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Já possui uma conta?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Criar conta') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
