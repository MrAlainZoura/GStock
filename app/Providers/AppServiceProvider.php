<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('formaMille', function ($expression) {
            return "<?php echo number_format($expression, 0, ',', ' '); ?>";
        });
        Blade::if('beforelimit', function ($date) {
            $limit = $date->copy()->addDay()->setTime(12, 0, 0);
            return now() <= $limit;
        });
        

        Schema::defaultStringLength(191);
    }
}
