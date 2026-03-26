<?php

use App\Http\Controllers\Guest\OnlineEntrancePaymentController;
use App\Http\Controllers\Guest\WebsiteController;
use App\Http\Controllers\ResultCheckController;
use App\Services\NewsService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Homepage (Closure)
|--------------------------------------------------------------------------
*/
Route::get('/', function (NewsService $newsService) {
    $homeNews = $newsService->forHomePage();
    return view('home', [
        'featuredNews' => $homeNews['featured'],
        'moreNews' => $homeNews['more'],
    ]);
})->name('home');

/*
|--------------------------------------------------------------------------
| Website Controller Routes
|--------------------------------------------------------------------------
*/
Route::controller(WebsiteController::class)->group(function () {
    // General Pages
    Route::get('/about-us', 'aboutUs')->name('about_us');
    Route::get('/faqs', 'faqs')->name('faqs');
    Route::get('/admin-process', 'adminProcess')->name('admin_process');
    Route::get('/academic-overview', 'academicOverview')->name('academic_overview');

    // News
    Route::get('/news', 'newsIndex')->name('news');
    Route::get('/news/{news}', 'newsShow')->name('news.show');

    // Application Form
    Route::prefix('apply-online')->group(function () {
        Route::get('/', 'applyOnline')->name('apply_online');
        Route::post('/', 'applyOnlineStore')->middleware('throttle:10,1')->name('apply_online.store');
    });
});

/*
|--------------------------------------------------------------------------
| Entrance Payment Routes
|--------------------------------------------------------------------------
*/
Route::prefix('apply-online')->controller(OnlineEntrancePaymentController::class)->group(function () {
    Route::get('/verify-payment', 'verify')->name('verify_payment');
    Route::get('/{entrance}/pay', 'show')->name('pay_online');
    Route::post('/{entrance}/pay', 'store')->middleware('throttle:10,1')->name('pay_online.store');
});

/*
|--------------------------------------------------------------------------
| Result Checker Routes
|--------------------------------------------------------------------------
*/
Route::get('/result', [ResultCheckController::class, 'index'])->name('result.check');

/*
|--------------------------------------------------------------------------
| Authentication & Role-Based Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/teacher.php';
