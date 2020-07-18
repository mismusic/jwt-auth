<?php

namespace MisMusic\JWTAuth\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MisMusic\JWTAuth\Console\JWTSecretGenerate;
use MisMusic\JWTAuth\JWTAuth;

class JWTServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('JWTAuth', function (Application $app) {
            return $app->make(JWTAuth::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('jwt.php'),
        ], 'config');

        // 将扩展包默认配置和应用的已发布副本配置合并在一起
        $this->mergeConfigFrom( __DIR__ . '/../../config/config.php', 'jwt');

        // 注册扩展包的 Artisan 命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                JWTSecretGenerate::class,
            ]);
        }

    }
}
