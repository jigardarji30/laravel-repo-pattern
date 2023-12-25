<?php

namespace Jigardarji\LaravelRepoPattern;

use Illuminate\Support\ServiceProvider;

class LaravelRepoPatternGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(\Jigardarji\LaravelRepoPattern\Console\Commands\MakeRepoPattern::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/stubs' => resource_path('crud-stubs')
        ]);
    }
}
