<?php

use App\Http\Controllers\Guest\WebsiteController;
use App\Http\Controllers\ResultCheckController;
use App\Services\NewsService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function (NewsService $newsService) {
    $homeNews = $newsService->forHomePage();

    return view('home', [
        'featuredNews' => $homeNews['featured'],
        'moreNews' => $homeNews['more'],
    ]);
})->name('home');

Route::get('/about-us', [WebsiteController::class, 'aboutUs'])->name('about_us');
Route::get('/vision-mission', [WebsiteController::class, 'visionMission'])->name('vision_mission');
Route::get('/faqs', [WebsiteController::class, 'faqs'])->name('faqs');
Route::get('/admin-process', [WebsiteController::class, 'adminProcess'])->name('admin_process');
Route::get('/apply-online', [WebsiteController::class, 'applyOnline'])->name('apply_online');
Route::get('/academic-overview', [WebsiteController::class, 'academicOverview'])->name('academic_overview');
Route::get('/academic-curriculum', [WebsiteController::class, 'academicCurriculum'])->name('academic_curriculum');
Route::get('/news', [WebsiteController::class, 'newsIndex'])->name('news');
Route::get('/news/{news}', [WebsiteController::class, 'newsShow'])->name('news.show');

Route::get('/result', [ResultCheckController::class, 'index'])->name('result.check');

/*
|--------------------------------------------------------------------------
| Authentication Routes — Guest (Admin and Teacher Login)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes (auth:admin)
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Teacher Routes (auth:teacher)
|--------------------------------------------------------------------------
*/
require __DIR__.'/teacher.php';
