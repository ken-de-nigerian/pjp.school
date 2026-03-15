<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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
                $view->with('layoutTeacher', auth()->guard('teacher')->user());
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
