<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="vards" :value="__('Vārds')" />
            <x-text-input id="vards" class="block mt-1 w-full" type="text" name="vards" :value="old('vards')" required autofocus autocomplete="given-name" />
            <x-input-error :messages="$errors->get('vards')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="uzvards" :value="__('Uzvārds')" />
            <x-text-input id="uzvards" class="block mt-1 w-full" type="text" name="uzvards" :value="old('uzvards')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('uzvards')" class="mt-2" />
        </div>

        <!-- Personal Code -->
        <div class="mt-4">
            <x-input-label for="personas_kods" :value="__('Personas kods')" />
            <x-text-input id="personas_kods" class="block mt-1 w-full" type="text" name="personas_kods" :value="old('personas_kods')" required />
            <x-input-error :messages="$errors->get('personas_kods')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="adrese" :value="__('Addrese')" />
            <x-text-input id="adrese" class="block mt-1 w-full" type="text" name="adrese" :value="old('adrese')" required autocomplete="street-address" />
            <x-input-error :messages="$errors->get('adrese')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="lietotaja_epasts" :value="__('E-pasts')" />
            <x-text-input id="lietotaja_epasts" class="block mt-1 w-full" type="email" name="lietotaja_epasts" :value="old('lietotaja_epasts')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('lietotaja_epasts')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="parole" :value="__('Parole')" />
            <x-text-input id="parole" class="block mt-1 w-full" type="password" name="parole" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('parole')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="parole_confirmation" :value="__('Apstiprini paroli')" />
            <x-text-input id="parole_confirmation" class="block mt-1 w-full" type="password" name="parole_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('parole_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Jau ir konts?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
