<?php 

namespace Laraket\Simples;

use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as ESClientBuilder;

class SimplesServiceProvider extends ServiceProvider 
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    Console\ImportCommand::class,
                    Console\FlushCommand::class,
                ]
            );
        }
    }
    public function register()
    {

        $this->mergeConfigFrom(__DIR__.'/../config/database.php', 'database.elasticsearch');

        $this->app->singleton(
            'es', 
            function () {
                $builder = ESClientBuilder::create()->setHosts(config('database.elasticsearch.hosts'));
                if (app()->environment() === 'local') {
                    // $builder->setLogger(app('log')->getMonolog());
                }
                return $builder->build();
            }
        );
    }
}