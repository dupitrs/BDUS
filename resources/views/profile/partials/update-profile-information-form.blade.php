<div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8">
    <section x-data="userProfile" x-init="originalEmail = $refs.emailInput.value">
        
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Profila informācija') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Atjauno sava profila informāciju.") }}
            </p>
        

        <form id="user-profile-form" method="post" action="{{ route('profile.update') }}"
              class="mt-6 space-y-6" @submit.prevent="handleFormSubmit($event)">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="vards" :value="__('Vārds')" />
                <x-text-input id="vards" name="vards" type="text" class="mt-1 block w-full"
                              :value="old('vards', $user->vards)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('vards')" />
            </div>

            <div>
                <x-input-label for="uzvards" :value="__('Uzvārds')" />
                <x-text-input id="uzvards" name="uzvards" type="text" class="mt-1 block w-full"
                              :value="old('uzvards', $user->uzvards)" required />
                <x-input-error class="mt-2" :messages="$errors->get('uzvards')" />
            </div>

            <div>
                <x-input-label for="personas_kods" :value="__('Personas kods')" />
                <x-text-input id="personas_kods" name="personas_kods" type="text" class="mt-1 block w-full"
                              :value="old('personas_kods', $user->personas_kods)" required />
                <x-input-error class="mt-2" :messages="$errors->get('personas_kods')" />
            </div>

            <div>
                <x-input-label for="adrese" :value="__('Adrese')" />
                <x-text-input id="adrese" name="adrese" type="text" class="mt-1 block w-full"
                              :value="old('adrese', $user->adrese)" required />
                <x-input-error class="mt-2" :messages="$errors->get('adrese')" />
            </div>

            <div>
                <x-input-label for="lietotaja_epasts" :value="__('E-pasts')" />
                <x-text-input id="lietotaja_epasts" name="lietotaja_epasts" type="email"
                              class="mt-1 block w-full" x-ref="emailInput"
                              :value="old('lietotaja_epasts', $user->lietotaja_epasts)" required />
                <x-input-error class="mt-2" :messages="$errors->get('lietotaja_epasts')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Saglabāt') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Saglabāts.') }}
                    </p>
                @endif
            </div>
        </form>

        <!-- Modālais dialogs -->
        <template x-if="showModal">
            <div x-transition
                 class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 w-full h-full rounded-xl">
                <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg w-full max-w-3xl mx-auto backdrop-blur-sm max-h-[90vh] overflow-auto">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Uzmanību!') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                        {{ __('Mainot e-pasta adresi, tiks pieprasīta atkārtota pieslēgšanās. Vai turpināt?') }}
                    </p>
                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-black rounded hover:bg-gray-400">
                            {{ __('Atcelt') }}
                        </button>
                        <button type="button" @click="submitForm"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-black rounded hover:bg-gray-400">
                            {{ __('Apstiprināt') }}
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- AlpineJS -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('userProfile', () => ({
                    showModal: false,
                    originalEmail: '',

                    handleFormSubmit(e) {
                        const email = this.$refs.emailInput.value;
                        if (email !== this.originalEmail) {
                            this.showModal = true;
                        } else {
                            e.target.submit();
                        }
                    },

                    submitForm() {
                        this.showModal = false;
                        document.getElementById('user-profile-form').submit();
                    }
                }));
            });
        </script>
    </section>
</div>
