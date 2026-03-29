<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entrance;
use App\Support\Coercion;
use App\Support\XlsxExport;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class OnlineEntranceController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();

        return view('admin.online-entrance.index', [
            'applicants' => $applicants,
        ]);
    }

    public function applicantsPdf(): View
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();

        return view('admin.online-entrance.applicants-pdf', [
            'applicants' => $applicants,
        ]);
    }

    public function applicantsExcel(): StreamedResponse
    {
        Gate::authorize('viewAny', Entrance::class);

        $applicants = Entrance::query()->ordered()->get();
        $headers = ['#', 'ID', 'Name', 'Gender', 'DOB', 'Current School', 'Class', 'Origin (State / LGA)'];
        $rows = [];
        foreach ($applicants as $index => $a) {
            $sur = Coercion::string($a->getAttribute('candidates_surname'));
            $first = Coercion::string($a->getAttribute('candidates_firstname'));
            $mid = Coercion::string($a->getAttribute('candidates_middlename'));
            $name = trim($sur.', '.$first.' '.$mid);
            $idVal = Coercion::string($a->getAttribute('uniqueID'));
            if ($idVal === '') {
                $idVal = Coercion::string($a->getKey());
            }

            $gender = Coercion::string($a->getAttribute('selectgender'));
            $dob = Coercion::string($a->getAttribute('candidates_date_of_birth'));
            $school = Coercion::string($a->getAttribute('candidates_current_school'));
            $class = Coercion::string($a->getAttribute('candidates_current_class'));
            $state = Coercion::string($a->getAttribute('states'));
            $lga = Coercion::string($a->getAttribute('candidates_lga'));

            $rows[] = [
                $index + 1,
                $idVal,
                $name !== '' ? $name : '—',
                $gender !== '' ? $gender : '—',
                $dob !== '' ? $dob : '—',
                $school !== '' ? $school : '—',
                $class !== '' ? $class : '—',
                $state.' / '.($lga !== '' ? $lga : '—'),
            ];
        }

        $file = 'entrance-applicants-'.now()->format('Y-m-d').'.xlsx';

        return XlsxExport::stream($file, $headers, $rows);
    }

    public function show(Entrance $entrance): View
    {
        Gate::authorize('viewAny', Entrance::class);

        return view('admin.online-entrance.show', [
            'applicant' => $entrance,
        ]);
    }
}
