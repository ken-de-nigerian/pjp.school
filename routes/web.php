<?php

use App\Http\Controllers\Guest\OnlineEntrancePaymentController;
use App\Http\Controllers\Guest\ResultCheckController;
use App\Http\Controllers\Guest\WebsiteController;
use App\Services\NewsService;
use App\Support\Coercion;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/system-setup', function () {
    try {
        $output = [];

        // 1. Run migrations
        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'Migrations: Success.';

        // 2. Storage Link Check
        if (! is_link(public_path('storage'))) {
            Artisan::call('storage:link');
            $output[] = 'Storage: Link created.';
        }

        // 3. Clear Sessions based on a driver
        $driver = Coercion::string(config('session.driver'), '');

        if ($driver === 'file') {
            // Delete all files in storage/framework/sessions except .gitignore
            File::cleanDirectory(storage_path('framework/sessions'));
            $output[] = 'Sessions: Cleared file-based sessions.';
        } elseif ($driver === 'database') {
            // Truncate the session table if it exists
            $table = Coercion::string(config('session.table'), 'sessions');
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $output[] = 'Sessions: Truncated database sessions.';
            }
        } else {
            // For Redis/Memcached, cache:clear usually handles it
            Artisan::call('cache:clear');
            $output[] = "Sessions: Cleared via cache:clear ($driver driver).";
        }

        // 4. Final Optimization Clear
        Artisan::call('optimize:clear');
        $output[] = 'Optimization: System caches cleared.';

        return response()->json(['status' => 'success', 'log' => $output]);

    } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
})->middleware('auth:admin');

/*
|--------------------------------------------------------------------------
| Homepage (Closure)
|--------------------------------------------------------------------------
*/
Route::get('/', function (NewsService $newsService) {
    $homeNews = $newsService->forHomePage();

    return view('guest.pages.home', [
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
