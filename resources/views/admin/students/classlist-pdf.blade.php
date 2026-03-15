<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List — {{ e($selectedClass) }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; font-size: 12px; line-height: 1.4; color: #1a1a1a; margin: 0; padding: 16px; }
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
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" class="btn-print" onclick="window.print();">
            <span>🖨</span> Print / Save as PDF
        </button>
    </div>

    <h1>Class List — {{ e($selectedClass) }}</h1>
    <p class="meta">Generated {{ now()->format('j M Y, g:i A') }} · {{ $students->count() }} student(s)</p>

    <table>
        <thead>
            <tr>
                <th style="width: 2.5em;">#</th>
                <th>Name</th>
                <th>Reg. number</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $s)
                @php $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? '')); @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $fullName ?: '—' }}</td>
                    <td>{{ $s->reg_number ?? '—' }}</td>
                    <td>{{ e($s->class ?? '') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #666;">No students in this class.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
