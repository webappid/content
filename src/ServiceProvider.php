<?php
namespace WebAppId\Content;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;;
class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind('content', function() {
            return new Content;
        });
        $this->commands(\WebAppId\Content\Commands\SeedCommand::class);
    }
    public function boot()
    {
        if ($this->isLaravel53AndUp()) {
            $this->loadMigrationsFrom(__DIR__.'/migrations');
        } else {
            $this->publishes([
                __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations'
            ], 'migrations');
        }
    }
    protected function isLaravel53AndUp()
    {
        return version_compare($this->app->version(), '5.3.0', '>=');
    }
}