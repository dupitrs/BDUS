<div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8">
    <section x-data="adminProfile" x-init="originalEmail = $refs.emailInput.value">
        
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Profila informācija') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Atjauno administratora profila informāciju.') }}
            </p>
        

        <form id="admin-profile-form" method="post" action="{{ route('admin.profile.update') }}"
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
                <x-input-label for="administratora_epasts" :value="__('E-pasts')" />
                <x-text-input id="administratora_epasts" name="administratora_epasts" type="email"
                              class="mt-1 block w-full" x-ref="emailInput"
                              :value="old('administratora_epasts', $user->administratora_epasts)" required />
                <x-input-error class="mt-2" :messages="$errors->get('administratora_epasts')" />
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

        <!-- Modālais dialogs (ar x-if) -->
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
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded hover:bg-gray-400">
                            {{ __('Atcelt') }}
                        </button>
                        <button type="button" @click="submitForm"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded hover:bg-gray-400">
                            {{ __('Apstiprināt') }}
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- AlpineJS -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('adminProfile', () => ({
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
                        document.getElementById('admin-profile-form').submit();
                    }
                }));
            });
        </script>
    </section>
</div>
