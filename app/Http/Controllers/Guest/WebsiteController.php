<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\View\View;

final class WebsiteController extends Controller
{
    public function aboutUs(): View
    {
        return view('guest.pages.about-us', ['title' => 'About Us']);
    }

    public function visionMission(): View
    {
        return view('guest.pages.vision-mission', ['title' => 'Vision & Mission']);
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
        return view('guest.pages.apply-online', ['title' => 'Apply Online']);
    }

    public function academicOverview(): View
    {
        return view('guest.pages.academic-overview', ['title' => 'Academic Overview']);
    }

    public function academicCurriculum(): View
    {
        return view('guest.pages.academic-curriculum', ['title' => 'Academic Curriculum']);
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
}
