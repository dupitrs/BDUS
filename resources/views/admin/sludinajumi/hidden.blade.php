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

            @media (max-width: 700px) {
                .custom-flex {
                    display: flex;
                    flex-direction: column;
                }
            }

            @media (min-width: 701px) {
                .custom-flex {
                    display: flex;
                    flex-direction: row;
                }
            }
        </style>

        <div class="flex items-center justify-between relative custom-header">
            <div class="left-btn">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-700 text-black px-4 py-2 rounded hover:bg-gray-700 transition">
                    ← Atpakaļ uz sākumu
                </a>
            </div>

            <div class="center-title flex">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    Slēptie sludinājumi
                </h2>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ($sludinajumi as $sludinajums)
                    <div class="bg-white dark:bg-gray-800 shadow rounded p-6 mb-6">
                        <div class="custom-flex gap-6 items-start">
                            {{-- Teksts --}}
                            <div class="flex-1 break-words space-y-3 w-full">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ $sludinajums->nosaukums }}
                                </h3>

                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                    {!! nl2br(e($sludinajums->apraksts)) !!}
                                </p>

                                <p class="text-xs text-gray-500">
                                    Norises datums: {{ $sludinajums->norises_datums }}
                                </p>
                            </div>

                            {{-- Bilde --}}
                            @if ($sludinajums->bilde)
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{ asset('storage/' . $sludinajums->bilde) }}"
                                        alt="Sludinājuma bilde"
                                        class="rounded shadow object-cover transition-transform duration-300 hover:scale-105"
                                        style="width: 240px; height: 240px;"
                                    />
                                </div>
                            @endif
                        </div>

                        {{-- Admin pogas --}}
                        <div class="flex gap-2 pt-2">
                            <form method="POST" action="{{ route('admin.sludinajumi.unhide', $sludinajums->ID) }}">
                                @csrf
                                @method('PUT')
                                <button class="px-3 py-1 bg-green-600 text-black rounded hover:bg-green-700">
                                    Atkārtoti parādīt
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.sludinajumi.destroy', $sludinajums->ID) }}"
                                  onsubmit="return confirm('Dzēst pavisam?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Dzēst
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
