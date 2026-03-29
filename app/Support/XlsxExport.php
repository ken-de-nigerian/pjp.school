<?php

declare(strict_types=1);

namespace App\Support;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class XlsxExport
{
    /**
     * @param  list<string>  $headers
     * @param  list<list<string|int|float|null>>  $rows
     */
    public static function stream(string $downloadFileName, array $headers, array $rows): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $data = [$headers];
        foreach ($rows as $row) {
            $data[] = array_map(static fn ($v) => $v ?? '', $row);
        }
        $sheet->fromArray($data);

        return response()->streamDownload(
            static function () use ($spreadsheet): void {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            $downloadFileName,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public static function sanitizeFileSegment(string $name, string $fallback = 'export'): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9._-]+/', '-', $name);
        $clean = is_string($clean) ? trim($clean, '-') : '';

        return $clean !== '' ? $clean : $fallback;
    }
}
