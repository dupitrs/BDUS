<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Iespējas
        </h2>
    </x-slot>

    <style>
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Sveiciens --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-lg font-semibold text-gray-900 dark:text-black">
                    @if (session('user_type') === 'admin')
                        Sveiki, {{ auth('admin')->user()->vards }}!
                    @else
                        Sveiki, {{ auth()->user()->vards }}!
                    @endif
                </p>
            </div>

            {{-- Admin pogas --}}
            @if (session('user_type') === 'admin')
                <div class="text-right">
                    <a href="{{ route('admin.sludinajumi.create') }}" class="bg-blue-600 text-black px-4 py-2 rounded ">+ Pievienot sludinājumu</a>
                    <a href="{{ route('admin.sludinajumi.hidden') }}" class="bg-gray-600 text-black px-4 py-2 rounded ">👁️ Slēptie sludinājumi</a>
                </div>
            @endif

            {{-- Ziņa --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Sludinājumu saraksts --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ($sludinajumi as $sludinajums)
                    <div class="bg-white dark:bg-gray-800 shadow rounded p-6 mb-6">
                        <div class="custom-flex gap-6 items-start">
                            {{-- Teksts --}}
                            <div class="flex-1 space-y-3 w-full">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-black">{{ $sludinajums->nosaukums }}</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-800 whitespace-pre-line">{!! nl2br(e($sludinajums->apraksts)) !!}</p>
                                <p class="text-sm text-gray-800">Norises datums: {{ \Carbon\Carbon::parse($sludinajums->norises_datums)->format('d/m/Y') }}</p>
                            </div>

                            {{-- Bilde --}}
                            @if ($sludinajums->bilde)
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $sludinajums->bilde) }}" alt="Sludinājuma bilde"
                                        class="rounded shadow object-cover transition-transform duration-300 hover:scale-105"
                                        style="width: 240px; height: 240px;" />
                                </div>
                            @endif
                        </div>

                        {{-- Hover pieteikušies --}}
                        {{-- Pieteikuma poga ar hover un klikšķa funkciju --}}

                        <div x-data="{ show: false, hovering: false }" @click.outside="show = false" class="relative mt-4 flex justify-end">

                            {{-- Poga labajā pusē --}}
                            <button
                                @mouseenter="hovering = true; show = true"
                                @mouseleave="hovering = false"
                                @click="show = !show"
                                class="px-3 py-1 bg-indigo-600 text-gray-400 rounded hover:bg-indigo-700 transition text-sm text-center"
                            >
                                 Skatīt pieteikušos
                            </button>

                            {{-- Saraksts (zem pogas) --}}
                            <div
                                x-show="show"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute right-0 bg-white text-black shadow-lg rounded p-4 mt-2 z-20 w-64"
                                @mouseenter="hovering = true"
                                @mouseleave="hovering = false; setTimeout(() => { if (!hovering) show = false }, 200)"
                                style="display: none;"
                            >
                                @php $lietotaji = $sludinajums->pieteikusies ?? []; @endphp

                                @if (count($lietotaji) === 0)
                                    <p class="text-sm text-gray-600">Esi pirmais, kas piesakās!</p>
                                @else
                                    <p class="text-sm font-semibold text-gray-800 mb-2">Pieteikušies:</p>
                                    <ul class="text-sm list-disc pl-5 space-y-1">
                                        @foreach ($lietotaji as $lietotajs)
                                            <li>{{ $lietotajs->vards }} {{ $lietotajs->uzvards }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.data('hoverToggle', () => ({
                                    show: false,
                                    showPersistent: false,
                                }));
                            });
                        </script>


                        {{-- Lietotājam pieteikšanās forma --}}
                        @if (session('user_type') !== 'admin' && $sludinajums->can_apply)
                            <form method="POST" action="{{ route('pieteikties', $sludinajums->ID) }}" class="mt-2">
                                @csrf
                                <button class="text-sm text-blue-600 hover:underline" type="submit">
                                    @if ($pieteikumi->contains(fn($p) => $p->lietotaja_epasts === auth()->user()->lietotaja_epasts && $p->ID == $sludinajums->ID && $p->statuss == 1))
                                        <span class="text-red-600 font-semibold">Atcelt pieteikumu</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Pieteikties</span>
                                    @endif
                                </button>
                            </form>
                        @elseif(session('user_type') !== 'admin')
                            <p class="text-sm text-gray-400 mt-2"> Pieteikšanās ir slēgta</p>
                        @endif



                        {{-- Admin pogas --}}
                        @if (session('user_type') === 'admin')
                            <div class="flex gap-2 pt-4">
                                <a href="{{ route('admin.sludinajumi.edit', $sludinajums->ID) }}"
                                   class="px-3 py-1 bg-yellow-500 text-black rounded text-sm hover:bg-yellow-600">Rediģēt</a>

                                <form method="POST" action="{{ route('admin.sludinajumi.hide', $sludinajums->ID) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1 bg-gray-600 text-black rounded text-sm ">Paslēpt</button>
                                </form>

                                <form method="POST" action="{{ route('admin.sludinajumi.destroy', $sludinajums->ID) }}"
                                      onsubmit="return confirm('Vai tiešām dzēst šo sludinājumu?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-black rounded text-sm hover:bg-red-700">Dzēst</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>


