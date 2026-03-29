<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\ChecklistServiceContract;
use App\Contracts\FeeServiceContract;
use App\Contracts\NotificationServiceContract;
use App\Contracts\ResultRemarkServiceContract;
use App\Contracts\ResultRepositoryContract;
use App\Contracts\ResultServiceContract;
use App\Models\Notification;
use App\Models\Teacher;
use App\Policies\TeacherPolicy;
use App\Repositories\ResultRepository;
use App\Services\ChecklistService;
use App\Services\FeeService;
use App\Services\NotificationService;
use App\Services\ResultRemarkService;
use App\Services\ResultService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationServiceContract::class, NotificationService::class);
        $this->app->bind(ResultServiceContract::class, ResultService::class);
        $this->app->bind(ResultRepositoryContract::class, ResultRepository::class);
        $this->app->bind(ResultRemarkServiceContract::class, ResultRemarkService::class);
        $this->app->bind(FeeServiceContract::class, FeeService::class);
        $this->app->bind(ChecklistServiceContract::class, ChecklistService::class);
    }

    public function boot(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());

        Gate::policy(Teacher::class, TeacherPolicy::class);

        View::composer('*', function (ViewContract $view): void {
            $view->with('layoutRoute', request()->route()?->getName());
            if (auth()->guard('admin')->check()) {
                $admin = auth()->guard('admin')->user();
                if ($admin) {
                    $admin->load('role');
                    $view->with([
                        'layoutRole' => $admin->role,
                        'layoutAdmin' => $admin,
                        'layoutNotifications' => Notification::query()->orderByDesc('date_added')->limit(10)->get(),
                    ]);
                }
            }
            if (auth()->guard('teacher')->check()) {
                $view->with([
                    'layoutTeacher' => auth()->guard('teacher')->user(),
                    'layoutNotifications' => Notification::query()->orderByDesc('date_added')->limit(10)->get(),
                ]);
            }
        });

        AuthenticationException::redirectUsing(function (Request $request, array $guards) {
            if ($request->expectsJson()) {
                return null;
            }
            if (in_array('teacher', $guards, true)) {
                return route('teacher.login');
            }

            return route('admin.login');
        });
    }
}
