<x-app-layout>
    <x-slot name="header">
        <style>
            @media (max-width: 700px) {
                .custom-header {
                    flex-direction: column;
                    height: auto;
                    padding-top: 1rem;
                    padding-bottom: 1rem;
                }

                .custom-header .left-btn {
                    position: static;
                    margin-bottom: 0.5rem;
                    text-align: center;
                }

                .custom-header .center-title {
                    justify-content: center;
                    width: 100%;
                }
            }

            @media (min-width: 701px) {
                .custom-header {
                    flex-direction: row;
                    height: 4rem;
                }

                .custom-header .left-btn {
                    position: absolute;
                    left: 0;
                    margin-left: 1rem;
                }

                .custom-header .center-title {
                    justify-content: center;
                    width: 100%;
                }
            }
        </style>

        <div class="flex items-center justify-between relative custom-header">
            <div class="left-btn">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-700 text-black px-4 py-2 rounded hover:bg-gray-800 transition">
                    ← Atpakaļ uz sākumu
                </a>
            </div>

            <div class="center-title flex">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    Rediģēt sludinājumu
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 text-green-600 dark:text-green-400 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.sludinajumi.update', $sludinajums->ID) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  x-data="fileUpload()">
                @csrf
                @method('PUT')

                {{-- Nosaukums --}}
                <div class="mb-4">
                    <x-input-label for="nosaukums" :value="__('Nosaukums')" />
                    <x-text-input type="text" name="nosaukums" id="nosaukums" class="block w-full mt-1"
                                  value="{{ old('nosaukums', $sludinajums->nosaukums) }}" required />
                    <x-input-error :messages="$errors->get('nosaukums')" class="mt-2" />
                </div>

                {{-- Apraksts --}}
                <div class="mb-4">
                    <x-input-label for="apraksts" :value="__('Apraksts')" />
                    <textarea name="apraksts" id="apraksts" rows="8"
                              class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('apraksts', $sludinajums->apraksts) }}</textarea>
                    <x-input-error :messages="$errors->get('apraksts')" class="mt-2" />
                </div>

                {{-- Datums --}}
                <div class="mb-4">
                    <x-input-label for="norises_datums" :value="__('Norises datums')" />
                    <x-text-input
                        type="date"
                        name="norises_datums"
                        id="norises_datums"
                        style="width: 200px;"
                        class="mt-1"
                        value="{{ old('norises_datums', \Carbon\Carbon::parse($sludinajums->norises_datums)->format('Y-m-d')) }}"
                        required
                        onkeydown="return false;"
                    />
                    <x-input-error :messages="$errors->get('norises_datums')" class="mt-2" />
                </div>

                {{-- Attēls --}}
                <div class="mb-4 border-2 border-dashed rounded-md p-6 text-center cursor-pointer bg-white dark:bg-gray-800"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="handleDrop($event)"
                     :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-gray-300'">

                    <p class="text-gray-500 dark:text-gray-300">Ievelc attēlu šeit vai izvēlies manuāli</p>
                    <input type="file" name="bilde" id="bilde" class="hidden" x-ref="fileInput" @change="handleFileChosen">
                    <button type="button"
                            class="mt-2 px-4 py-2 bg-blue-600 text-black rounded hover:bg-blue-700"
                            @click="$refs.fileInput.click()">
                        Izvēlēties failu
                    </button>

                    {{-- Preview --}}
                    <template x-if="previewUrl">
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Jaunais attēls:</p>
                            <img :src="previewUrl" alt="Attēla priekšskatījums" class="max-h-48 mx-auto rounded shadow">
                        </div>
                    </template>

                    {{-- Pašreizējais attēls --}}
                    @if ($sludinajums->bilde && !old('bilde'))
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Pašreizējais attēls:</p>
                            <img src="{{ asset('storage/' . $sludinajums->bilde) }}" alt="Sludinājuma attēls"
                                 class="max-h-48 rounded shadow mt-2 mx-auto">
                        </div>
                    @endif
                </div>

                {{-- Saglabāšanas poga --}}
                <div class="flex justify-end">
                    <x-primary-button>
                        {{ __('💾 Saglabāt izmaiņas') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine.js skripts --}}
    <script>
        function fileUpload() {
            return {
                dragging: false,
                previewUrl: null,
                handleFileChosen(event) {
                    const file = event.target.files[0];
                    this.previewFile(file);
                },
                handleDrop(event) {
                    this.dragging = false;
                    const file = event.dataTransfer.files[0];
                    this.$refs.fileInput.files = event.dataTransfer.files;
                    this.previewFile(file);
                },
                previewFile(file) {
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewUrl = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    </script>
</x-app-layout>
