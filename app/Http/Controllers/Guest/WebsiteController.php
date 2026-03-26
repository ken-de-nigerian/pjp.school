<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOnlineEntranceApplicationRequest;
use App\Models\Entrance;
use App\Models\News;
use App\Models\Setting;
use App\Services\NewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Random\RandomException;
use Throwable;

final class WebsiteController extends Controller
{
    public function aboutUs(): View
    {
        return view('guest.pages.about-us', ['title' => 'About Us']);
    }

    public function faqs(): View
    {
        return view('guest.pages.faqs', [
            'title' => 'FAQs',
            'faqTopics' => config('faqs.topics'),
        ]);
    }

    public function adminProcess(): View
    {
        return view('guest.pages.admin-process', ['title' => 'Admission Process']);
    }

    public function applyOnline(): View
    {
        $settings = Setting::getCached();
        return view('guest.pages.apply-online', ['title' => 'Apply Online', 'settings' => $settings]);
    }

    /**
     * @throws Throwable
     */
    public function applyOnlineStore(StoreOnlineEntranceApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $entrance = DB::transaction(function () use ($validated) {
            $uniqueId = $this->generateEntranceUniqueId();

            return Entrance::query()->create([
                'uniqueID' => $uniqueId,
                'candidates_surname' => $validated['surname'],
                'candidates_firstname' => $validated['firstname'],
                'candidates_middlename' => $validated['middlename'] ?? null,
                'candidates_date_of_birth' => $validated['dob'],
                'candidates_place_of_birth' => $validated['place_of_birth'],
                'candidates_nationality' => $validated['country'],
                'states' => $validated['state'],
                'candidates_lga' => $validated['lga'],
                'candidates_town' => $validated['town'],
                'candidates_village' => $validated['village'] ?? '',
                'selectgender' => $validated['gender'],
                'candidates_current_school' => $validated['current_school'],
                'candidates_current_class' => $validated['current_class'],
                'applying_for' => $validated['applying_for'],
                'certificate' => $validated['has_leaving_cert'],
                'blood_group' => $validated['blood_group'],
                'disability' => $validated['disability'] ?? null,
                'sickness' => $validated['special_care'] ?? null,
                'fathers_surname' => $validated['father_surname'],
                'fathers_firstname' => $validated['father_firstname'],
                'fathers_middlename' => $validated['father_middlename'] ?? null,
                'fathers_occupation' => $validated['father_occupation'],
                'fathers_address' => $validated['father_address'],
                'fathers_phone' => $validated['father_phone'],
                'mothers_surname' => $validated['mother_surname'],
                'mothers_firstname' => $validated['mother_firstname'],
                'mothers_middlename' => $validated['mother_middlename'] ?? null,
                'mothers_occupation' => $validated['mother_occupation'],
                'mothers_address' => $validated['mother_address'],
                'mothers_phone' => $validated['mother_phone'],
                'guardians_surname' => $validated['guardian_surname'] ?? null,
                'guardians_firstname' => $validated['guardian_firstname'] ?? null,
                'guardians_middlename' => $validated['guardian_middlename'] ?? null,
                'guardians_occupation' => $validated['guardian_occupation'] ?? null,
                'guardians_address' => $validated['guardian_address'] ?? null,
                'guardians_phone' => $validated['guardian_phone'] ?? null,
                'payment_mode' => 'Offline',
                'payment_status' => 2,
            ]);
        });

        return redirect()
            ->route('pay_online', $entrance->id)
            ->with('success', 'Your application was submitted successfully. Please save your reference ID: '.$entrance->uniqueID);
    }

    public function academicOverview(): View
    {
        return view('guest.pages.academic-overview', ['title' => 'Academic Overview']);
    }

    public function newsIndex(NewsService $newsService): View
    {
        return view('guest.pages.news-index', [
            'title' => 'News',
            'news' => $newsService->list(9),
        ]);
    }

    public function newsShow(News $news): View
    {
        return view('guest.pages.news-show', [
            'title' => $news->title,
            'news' => $news,
        ]);
    }

    /**
     * @throws RandomException
     */
    private function generateEntranceUniqueId(): string
    {
        do {
            $uniqueId = (string) random_int(100_000_000_000, 999_999_999_999);
        } while (Entrance::query()->where('uniqueID', $uniqueId)->exists());

        return $uniqueId;
    }
}
