<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e1b4b; margin: 24px; }
        .bar { height: 6px; background: #4f46e5; margin: 0 0 14px; }
        .brand { font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; color: #6d28d9; margin: 0 0 4px; }
        h1 { font-size: 18px; margin: 0 0 4px; color: #312e81; }
        .meta { color: #64748b; font-size: 11px; margin: 0 0 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e0e7ff; padding: 7px 6px; text-align: left; }
        th { font-size: 10px; text-transform: uppercase; color: #3730a3; background: #eef2ff; }
        tr:nth-child(even) td { background: #f8f7ff; }
        .right { text-align: right; }
        .foot { margin-top: 14px; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <div class="bar"></div>
    <p class="brand">Controle de estoque</p>
    <h1>{{ $title }}</h1>
    <p class="meta">Gerado em {{ $generatedAt->format('d/m/Y H:i') }} · {{ $summary }}</p>
    <table>
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th class="{{ ($column['align'] ?? '') === 'right' ? 'right' : '' }}">{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $index => $cell)
                        <td class="{{ ($columns[$index]['align'] ?? '') === 'right' ? 'right' : '' }}">{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <p class="foot">Documento gerado a partir dos registros do banco de dados.</p>
</body>
</html>
