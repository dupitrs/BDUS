<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.75rem; font-weight: bold; color: #2c3e50;">Lietotāji un kopējās nostrādātās stundas</h2>
    </x-slot>

   



    @if (session('success'))
        <div style="margin: 20px; padding: 12px; background-color: #eafaf1; border-left: 6px solid #28a745; color: #155724; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding: 20px;">
        {{-- Filtri --}}
        <form method="GET" action="{{ route('lietotaji.index') }}" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
            <input type="text" name="meklet_lietotaju" placeholder="Meklēt lietotāju..." value="{{ request('meklet_lietotaju') }}" style="padding: 6px; width: 250px;">

            <label class="datuma-filtrs">
                no <input type="date" name="no" value="{{ request('no') }}" style="padding: 6px;">
                līdz <input type="date" name="lidz" value="{{ request('lidz') }}" style="padding: 6px;">
            </label>

            <button type="submit" style="padding: 6px 12px; background-color: #3490dc; color: white; border: none; border-radius: 4px;">Filtrēt</button>
        </form>

        {{-- Tabula --}}
        <div class="responsive-table">
            <table border="1" cellspacing="0" cellpadding="10">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th>Vārds</th>
                        <th>Uzvārds</th>
                        <th>E-pasts</th>
                        <th>Personas kods</th>
                        <th>Adrese</th>
                        <th>Kopā apliecinātās stundas</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lietotaji as $index => $lietotajs)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#ffffff' : '#f4f6f8' }};">
                            <td>{{ $lietotajs->vards }}</td>
                            <td>{{ $lietotajs->uzvards }}</td>
                            <td>{{ $lietotajs->lietotaja_epasts }}</td>
                            <td>{{ $lietotajs->personas_kods }}</td>
                            <td>{{ $lietotajs->adrese }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ $lietotajs->kopa_stundas }}</td>
                            <td>
                                <button 
                                    onclick="openDeleteModal('{{ $lietotajs->vards }} {{ $lietotajs->uzvards }}', '{{ $lietotajs->lietotaja_epasts }}')"
                                    style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px;">
                                    Dzēst lietotāju
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modālais logs dzēšanai --}}
    <div id="deleteModal" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6);">
        <div id="deleteModalContent">
            <h2 style="margin-bottom: 20px;">Vai tiešām dzēst lietotāju <span id="deleteUserName" style="font-weight: bold;"></span>?</h2>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 5px;">Jā, dzēst</button>
                <button type="button" onclick="closeDeleteModal()" style="margin-left: 15px; padding: 8px 16px; background-color: #ccc; border: none; border-radius: 5px;">Atcelt</button>
            </form>
        </div>
    </div>
</x-app-layout>

{{-- CSS --}}
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
            margin-left: 0;
            flex-direction: column;
            align-items: flex-start;
        }
    }

    #deleteModalContent {
        background: white;
        max-width: 90%;
        width: 400px;
        margin: 10% auto;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
</style>

{{-- JS --}}
<script>
    function openDeleteModal(name, email) {
        document.getElementById('deleteUserName').textContent = name;
        document.getElementById('deleteForm').action = '/lietotaji/' + email;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
