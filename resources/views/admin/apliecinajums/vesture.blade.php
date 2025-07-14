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
                    Apliecinājumu vēsture
                </h2>
            </div>
        </div>
    </x-slot>

    

    @if (session('success'))
        <div style="margin: 20px; padding: 12px; background-color: #eafaf1; border-left: 6px solid #28a745; color: #155724; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding: 20px;">
        {{-- Meklēšanas forma --}}
        <form method="GET" action="{{ route('apliecinajums.vesture') }}" style="margin-bottom: 20px; display: flex; gap: 10px;">
            <input type="text" name="meklet" placeholder="Meklēt lietotāju..." value="{{ request('meklet') }}" style="padding: 6px; width: 300px;">
            <button type="submit" style="padding: 6px 12px; background-color: #3490dc; color: white; border: none; border-radius: 4px;">Meklēt</button>
        </form>

        <div class="responsive-table">
            <table border="1" cellspacing="0" cellpadding="10">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th>Lietotājs</th>
                        <th>Administrators</th>
                        <th>Izveides datums</th>
                        <th>PDF</th>
                        <th>Darbība</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($apliecinajumi as $index => $apl)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#ffffff' : '#f4f6f8' }};">
                            <td><strong>{{ $apl->lietotaja_vards }} {{ $apl->lietotaja_uzvards }}</strong><br><small>{{ $apl->lietotaja_epasts }}</small></td>
                            <td><strong>{{ $apl->admin_vards }} {{ $apl->admin_uzvards }}</strong><br><small>{{ $apl->administratora_epasts }}</small></td>
                            <td style="text-align: center;">{{ \Carbon\Carbon::parse($apl->izveides_datums)->format('d.m.Y') }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('apliecinajums.download', $apl->ID) }}" style="background-color: #3490dc; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Lejupielādēt PDF</a>
                            </td>
                            <td style="text-align: center;">
                                <form method="POST" action="{{ route('apliecinajums.delete', $apl->ID) }}" onsubmit="return confirm('Vai tiešām dzēst šo apliecinājumu?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Dzēst</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center;">Nav atrastu apliecinājumu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<style>
    table td, table th {
        white-space: normal;
        word-break: keep-all;
        overflow-wrap: break-word;
    }

    .responsive-table {
        display: block;
        width: 100%;
        overflow-x: auto;
    }

    .responsive-table table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
</style>
