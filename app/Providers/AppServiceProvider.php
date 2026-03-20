<?php

namespace App\Providers;

use App\Contracts\NotificationServiceContract;
use App\Contracts\ResultRepositoryContract;
use App\Contracts\ResultServiceContract;
use App\Models\Notification;
use App\Models\Teacher;
use App\Policies\TeacherPolicy;
use App\Repositories\ResultRepository;
use App\Services\NotificationService;
use App\Services\ResultService;
use Illuminate\Auth\AuthenticationException;
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
    }

    public function boot(): void
    {
        Gate::policy(Teacher::class, TeacherPolicy::class);

        View::composer('*', function ($view) {
            $view->with('layoutRoute', request()->route()?->getName());
            if (auth()->guard('admin')->check()) {
                $admin = auth()->guard('admin')->user();
                $admin->load('role');
                $view->with([
                    'layoutRole' => $admin->role,
                    'layoutAdmin' => $admin,
                    'layoutNotifications' => Notification::query()->orderByDesc('date_added')->limit(10)->get(),
                ]);
            }
            if (auth()->guard('teacher')->check()) {
                $view->with([
                    'layoutTeacher' => auth()->guard('teacher')->user(),
                    'layoutNotifications' => Notification::query()->orderByDesc('date_added')->limit(10)->get(),
                ]);
            }
        });

        AuthenticationException::redirectUsing(function ($request, array $guards) {
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
