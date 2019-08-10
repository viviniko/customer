<?php

namespace Viviniko\Customer;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Viviniko\Customer\Console\Commands\CustomerTableCommand;

class CustomerServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/customer.php' => config_path('customer.php'),
        ]);

        // Register commands
        $this->commands('command.customer.table');

        $config = $this->app['config'];

        Relation::morphMap([
            'customer.customer' => $config->get('customer.customer'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/customer.php', 'customer');

        $this->registerRepositories();

        $this->registerCommands();
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.customer.table', function ($app) {
            return new CustomerTableCommand($app['files'], $app['composer']);
        });
    }

    protected function registerRepositories()
    {
        $this->app->singleton(
            \Viviniko\Customer\Repositories\Customer\CustomerRepository::class,
            \Viviniko\Customer\Repositories\Customer\EloquentCustomer::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }
}