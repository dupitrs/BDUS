<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.75rem; font-weight: bold;">Ģenerēt apliecinājumu</h2>
    </x-slot>

    <div style="text-align: right; margin-bottom: 20px;">
        <a href="{{ route('apliecinajums.vesture') }}" style="padding: 8px 16px; background-color: #3490dc; color: white; border: none; border-radius: 4px; text-decoration: none; font-weight: bold;">
            Apliecinājumu vēsture
        </a>
    </div>

    <div style="padding: 20px;">
        <form method="POST" action="{{ route('apliecinajums.generate') }}">
            @csrf
            <label>Lietotājs:</label>
            <select name="lietotaja_epasts" required style="width: 100%; margin-bottom: 10px;">
                @foreach($lietotaji as $lietotajs)
                    <option value="{{ $lietotajs->lietotaja_epasts }}">
                        {{ $lietotajs->vards }} {{ $lietotajs->uzvards }} ({{ $lietotajs->lietotaja_epasts }})
                    </option>
                @endforeach
            </select>

            <label>Datumu posms:</label>
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <input type="date" name="no" required>
                <input type="date" name="lidz" required>
            </div>

            <button type="submit" style="padding: 8px 14px; background-color: #28a745; color: white; border: none; border-radius: 4px;">
                Ģenerēt PDF
            </button>
        </form>
    </div>
</x-app-layout>
