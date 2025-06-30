<section class="mt-8">
    
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Dzēst kontu') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Kad dzēsīsi kontu, visi dati tiks neatgriezeniski izdzēsti. Pirms konta dzēšanas ievadi savu paroli.') }}
        </p>
    

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="password" :value="__('Parole')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="current-password" required />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-danger-button>
                {{ __('Dzēst kontu') }}
            </x-danger-button>
        </div>
    </form>
</section>
