<?php

namespace App\Providers;

use App\Services\DataSnapService;
use App\Services\Impl\DataSnapServiceImpl;
use Illuminate\Support\ServiceProvider;
use App\Services\JwtService;
use App\Services\Impl\JwtServiceImpl;
use App\Services\LoginService;
use App\Services\Impl\LoginServiceImpl;
use App\Services\UserService;
use App\Services\Impl\UserServiceImpl;
use App\Services\NotificationService;
use App\Services\Impl\NotificationServiceImpl;
use App\Services\ProfileService;
use App\Services\Impl\ProfileServiceImpl;
use App\Services\UploadDataService;
use App\Services\Impl\UploadDataServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // jwt
        $this->app->singleton(JwtService::class,JwtServiceImpl::class);
        // login
        $this->app->singleton(LoginService::class, LoginServiceImpl::class);
        // Notification
        $this->app->singleton(NotificationService::class, NotificationServiceImpl::class);
        // Dashboard
        $this->app->singleton(ProfileService::class, ProfileServiceImpl::class);
        // user master
        $this->app->singleton(UserService::class, UserServiceImpl::class);
        // progress upload
        $this->app->singleton(UploadDataService::class, UploadDataServiceImpl::class);
        // data snap
        $this->app->singleton(DataSnapService::class, DataSnapServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
