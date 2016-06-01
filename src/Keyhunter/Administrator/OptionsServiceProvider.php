<?php namespace Keyhunter\Administrator;

use Illuminate\Support\ServiceProvider;
use Keyhunter\Administrator\Model\Settings;

class OptionsServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('app.options', function()
        {
            return (new Settings);//->lists('value', 'key');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['app.options'];
    }
}