@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unused pins — {{ e($session) }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "DM Sans", ui-sans-serif, system-ui, sans-serif; font-size: 12px; line-height: 1.4; color: #1a1a1a; margin: 0; padding: 16px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
        h1 { font-size: 18px; font-weight: 600; margin: 0 0 4px 0; }
        .meta { font-size: 11px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px 10px; border-bottom: 1px solid #e5e5e5; }
        th { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.02em; color: #444; background: #f5f5f5; }
        tr:nth-child(even) { background: #fafafa; }
        @media print { tr:nth-child(even) { background: #f9f9f9; } }
        .btn-print { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 13px; font-weight: 500; color: #fff; background: #2563eb; border: none; border-radius: 8px; cursor: pointer; margin-bottom: 16px; }
        .btn-print:hover { background: #1d4ed8; }
        td.font-mono { font-family: ui-monospace, monospace; }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" class="btn-print" onclick="window.print();">
            <span>🖨</span> Print / Save as PDF
        </button>
    </div>

    <h1>Unused pins — {{ e($session) }} Session</h1>
    <p class="meta">Generated {{ now()->format('j M Y, g:i A') }} · {{ $unused->count() }} pin(s)</p>

    <table>
        <thead>
            <tr>
                <th style="width: 2.5em;">#</th>
                <th>Serial #</th>
                <th>Pin</th>
                <th>Session</th>
                <th>Uploaded</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unused as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ e($row->serial_number ?? ('#' . ($index + 1))) }}</td>
                    <td class="font-mono">{{ e($row->pins) }}</td>
                    <td>{{ e($row->session) }}</td>
                    <td>{{ $row->upload_date ? Carbon::parse($row->upload_date)->format('M j, Y H:i') : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #666;">No unused pins.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
