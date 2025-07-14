<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.75rem; font-weight: bold; color: #2c3e50;">Pārskats par paveikto darbu</h2>
    </x-slot>

    @if (session('success'))
        <div style="margin: 20px; padding: 12px; background-color: #eafaf1; border-left: 6px solid #28a745; color: #155724; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding: 20px;">

        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                  {{-- + Pievienot ierakstu poga --}}
            @if ($user_type !== 'admin' && count($sludinajumi) > 0)
                <div style="margin-bottom: 20px;">
                    <button onclick="document.getElementById('modal').style.display='block'" style="background-color: #3490dc; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold;">
                        + Pievienot ierakstu
                    </button>
                </div>
            @endif
            {{-- Lietotājam – kopējās apliecinātās stundas --}}
            @if ($user_type !== 'admin')
                <div style="margin: 0 0 20px 0; font-size: 1.2rem; margin-right: 30px; margin-left:40px;">
                    <strong>Kopā nostrādātās stundas:</strong>
                    <span style="font-size: 2rem; color: #2c3e50; font-weight: bold;">
                        {{ $kopa_stundas }}
                    </span>
                </div>
            @endif

        </div>

        {{-- Filtri --}}
        <form method="GET" action="{{ route('parskats.index') }}" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
            @if ($user_type === 'admin')
                <input type="text" name="meklet_lietotaju" placeholder="Meklēt lietotāju..." value="{{ request('meklet_lietotaju') }}" style="padding: 6px; width: 200px;">
            

                <input type="text" name="meklet_sludinajumu" placeholder="Meklēt sludinājumu..." value="{{ request('meklet_sludinajumu') }}" style="padding: 6px; width: 200px;">

           
                <select name="statuss" style="padding: 6px; width: 250px;">
                    <option value="">Visi statusi</option>
                    <option value="gaida apstiprinājumu" {{ request('statuss') === 'gaida apstiprinājumu' ? 'selected' : '' }}>Gaida</option>
                    <option value="Apliecināts" {{ request('statuss') === 'Apliecināts' ? 'selected' : '' }}>Apliecināts</option>
                    <option value="Noraidīts" {{ request('statuss') === 'Noraidīts' ? 'selected' : '' }}>Noraidīts</option>
                </select>
            @endif

            <label class="datuma-filtrs">
                no <input type="date" name="no" value="{{ request('no') }}" style="padding: 6px;">
                līdz <input type="date" name="lidz" value="{{ request('lidz') }}" style="padding: 6px;">
            </label>


            <button type="submit" style="padding: 6px 12px; background-color: #3490dc; color: white; border: none; border-radius: 4px;">Filtrēt</button>
        </form>


        <div class="responsive-table">
            <table border="1" cellspacing="0" cellpadding="10">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        @php
                            $dir = request('direction') === 'asc' ? 'desc' : 'asc';
                            $arrow = request('direction') === 'asc' ? '▲' : '▼';
                        @endphp

                        @if ($user_type === 'admin')
                            <th>Lietotājs</th>
                            <th>Sludinājums</th>
                            <th>Stundas</th>
                            <th>Padarītais darbs</th>
                            <th>
                                <a href="{{ route('parskats.index', array_merge(request()->all(), ['sort' => 'sludinajums.norises_datums', 'direction' => $dir])) }}" style="text-decoration: none; color: inherit;">
                                    Datums {{ request('sort') === 'sludinajums.norises_datums' ? $arrow : '' }}
                                </a>
                            </th>
                            <th>Statuss</th>
                            <th>Darbība</th>
                        @else
                            <th>
                                <a href="{{ route('parskats.index', array_merge(request()->all(), ['sort' => 'sludinajums.norises_datums', 'direction' => $dir])) }}" style="text-decoration: none; color: inherit;">
                                    Datums {{ request('sort') === 'sludinajums.norises_datums' ? $arrow : '' }}
                                </a>
                            </th>
                            <th>Sludinājums</th>
                            <th>Stundas</th>
                            <th>Padarītais darbs</th>
                            <th>Statuss</th>
                            <th>Darbība</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ieraksti as $index => $ieraksts)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#ffffff' : '#f4f6f8' }};">
                            @if ($user_type === 'admin')
                                <td>{{ $ieraksts->vards }} {{ $ieraksts->uzvards }}<br><small>{{ $ieraksts->lietotaja_epasts }}</small></td>
                                <td>{{ $ieraksts->nosaukums }}</td>
                                <td style="text-align: center;">{{ $ieraksts->stundas }}</td>
                                <td>{{ $ieraksts->padaritais_darbs }}</td>
                                <td>{{ \Carbon\Carbon::parse($ieraksts->norises_datums)->format('d.m.Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('parskats.updateStatus', [$ieraksts->lietotaja_epasts, $ieraksts->ID]) }}">
                                        @csrf @method('PATCH')
                                        <select name="statuss" style="width: 100%;">
                                            <option value="gaida apstiprinājumu" {{ $ieraksts->statuss === 'gaida apstiprinājumu' ? 'selected' : '' }}>Gaida</option>
                                            <option value="Apliecināts" {{ $ieraksts->statuss === 'Apliecināts' ? 'selected' : '' }}>Apliecināts</option>
                                            <option value="Noraidīts" {{ $ieraksts->statuss === 'Noraidīts' ? 'selected' : '' }}>Noraidīts</option>
                                        </select>
                                </td>
                                <td>
                                        <button type="submit" style="background-color: #28a745; color:white; border:none; padding:5px 10px;">Saglabāt</button>
                                    </form>
                                    <form method="POST" action="{{ route('parskats.destroy', [$ieraksts->lietotaja_epasts, $ieraksts->ID]) }}" onsubmit="return confirm('Dzēst?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background-color: #dc3545; color:white; border:none; padding:5px 10px;">Dzēst</button>
                                    </form>
                                </td>
                            @else
                                <td>{{ \Carbon\Carbon::parse($ieraksts->norises_datums)->format('d.m.Y') }}</td>
                                <td>{{ $ieraksts->nosaukums }}</td>
                                <td style="text-align: center;">{{ $ieraksts->stundas }}</td>
                                <td>{{ $ieraksts->padaritais_darbs }}</td>
                                <td>
                                    @php
                                        $color = match($ieraksts->statuss) {
                                            'Apliecināts' => '#28a745',
                                            'Noraidīts' => '#dc3545',
                                            default => '#ffc107'
                                        };
                                    @endphp
                                    <span style="color:white; background-color: {{ $color }}; padding: 4px 8px; border-radius: 4px;">{{ $ieraksts->statuss }}</span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('parskats.destroy', [$ieraksts->lietotaja_epasts, $ieraksts->ID]) }}" onsubmit="return confirm('Dzēst ierakstu?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background-color: #dc3545; color:white; border:none; padding:5px 10px;">Dzēst</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal loga forma --}}
    @if ($user_type !== 'admin' && count($sludinajumi) > 0)
        <div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 999;">
            <div style="background-color: white; width: 90%; max-width: 500px; margin: 100px auto; padding: 20px; border-radius: 10px; position: relative;">
                <h3>Pievienot jaunu ierakstu</h3>
                <form method="POST" action="{{ route('parskats.store') }}">
                    @csrf

                    <label for="sludinajums_id">Sludinājums:</label>
                    <select name="ID" id="sludinajums_id" required style="width: 100%; margin-bottom: 10px;">
                        @php
                            $pieteiktieId = $ieraksti->pluck('ID')->toArray();
                        @endphp
                        @foreach ($sludinajumi as $sludinajums)
                            @if (!in_array($sludinajums->ID, $pieteiktieId))
                                <option value="{{ $sludinajums->ID }}">{{ $sludinajums->nosaukums }}</option>
                            @endif
                        @endforeach
                    </select>

                    <label>Stundas:</label>
                    <input type="number" name="stundas" required style="width: 100%; margin-bottom: 10px;">

                    <label>Padarītais darbs:</label>
                    <textarea name="padaritais_darbs" rows="4" required style="width: 100%; margin-bottom: 15px;"></textarea>

                    <div style="text-align: right;">
                        <button type="button" onclick="document.getElementById('modal').style.display='none'" style="margin-right: 10px;">Atcelt</button>
                        <button type="submit" style="background-color: #28a745; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Saglabāt</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
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

    .datuma-filtrs {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: 30px;
    }

    @media (max-width: 768px) {
        .datuma-filtrs {
            margin-left: 0; /* Mobilajā versijā noņem atstarpi pa kreisi */
        }
    }
</style>
