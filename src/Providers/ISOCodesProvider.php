<?php

namespace Juanparati\ISOCodes\Providers;

use Illuminate\Support\ServiceProvider;
use Juanparati\ISOCodes\ISOCodes;

class ISOCodesProvider extends ServiceProvider
{
    /**
     * Bootstrap service.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/isocodes.php' => $this->app->configPath('isocodes.php'),
        ]);
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/isocodes.php', 'isocodes');

        $this->app->singleton(ISOCodes::class, function () {
            $config = $this->app['config']['isocodes'] ?? [];
            return new ISOCodes(
                $config['datasets'] ?? [],
                $config['resolutions'] ?? [],
                $config['options'] ?? []
            );
        });
    }
}
