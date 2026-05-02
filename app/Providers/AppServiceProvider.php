<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->autoBindRepositories();
        $this->app->bind(
            \App\Repositories\Api\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\Api\Eloquent\ProductRepository::class
        );
        $this->app->bind(
            \App\Repositories\Api\Contracts\CategoryRepositoryInterface::class,
            \App\Repositories\Api\Eloquent\CategoryRepository::class
        );
    }

    /**
     * Auto-bind all Repository Interfaces to their implementations.
     */
    private function autoBindRepositories(): void
    {
        $interfacesPath = app_path('Repositories/Interfaces');

        if (! is_dir($interfacesPath)) {
            return;
        }

        foreach (scandir($interfacesPath) as $file) {
            if (! str_ends_with($file, 'Interface.php')) {
                continue;
            }

            $interfaceClass = 'App\\Repositories\\Interfaces\\' . str_replace('.php', '', $file);
            $concreteClass = 'App\\Repositories\\' . str_replace(['Interface.php'], ['.php'], $file);
            $concreteClass = str_replace('.php', '', $concreteClass);

            if (class_exists($concreteClass)) {
                $this->app->bind($interfaceClass, $concreteClass);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
