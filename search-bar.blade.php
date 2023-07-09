<div>
    <input type="text" wire:model.debounce.500ms="search" placeholder="Pesquisar" />
    <table>
        <thead>
            <tr>
                @foreach ($columnsNames as $columnName)
                    @if ($columnName)
                        <th>{{ $columnName }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($dataTable->isEmpty())
                <tr>
                    <td colspan="{{ count($columnsNames) }}">Nenhum resultado encontrado.</td>
                </tr>
            @else
                @php
                    $columnsNames = array_slice($columnsNames, 0, -1);
                @endphp
                @foreach ($dataTable as $data)
                    <tr>
                        @foreach (array_map(null, $data->toArray(), $columnsNames) as [$value, $columnName])
                            @if (\Carbon\Carbon::hasFormat($value, 'Y-m-d H:i:s'))
                                <td>{{ \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s') }}</td>
                            @elseif ($columnName)
                                <td>{{ $value }}</td>
                            @endif
                        @endforeach
                        @if ($showButtons)
                            <td>
                                <x-table-buttons-modals :id="$data->id" />
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{ $dataTable->links() }}
</div>
