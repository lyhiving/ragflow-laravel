<?php
/*
 * @Author: FutureMeng futuremeng@gmail.com
 * @Date: 2025-01-24 16:33:12
 * @LastEditors: FutureMeng futuremeng@gmail.com
 * @LastEditTime: 2025-01-24 16:52:18
 * @FilePath: /RAGFlow-laravel/src/ServiceProvider.php
 * @Description:
 *
 */

declare (strict_types = 1);

namespace RAGFlow\Laravel;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use RAGFlow;
use RAGFlow\Client;
use RAGFlow\Contracts\ClientContract;
use RAGFlow\Laravel\Commands\InstallCommand;
use RAGFlow\Laravel\Exceptions\ApiKeyIsMissing;

/**
 * @internal
 */
final class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ClientContract::class, static function (): Client {
            $apiKey      = config('ragflow.api_key');
            $apiEndpoint = config('ragflow.api_endpoint');

            if (! is_string($apiKey) || ! is_string($apiEndpoint)) {
                throw ApiKeyIsMissing::create();
            }

            return RAGFlow::factory()
                ->withApiKey($apiKey)
                ->withHttpClient(new \GuzzleHttp\Client(['timeout' => config('ragflow.request_timeout', 30)]))
                ->make();
        });

        $this->app->alias(ClientContract::class, 'ragflow');
        $this->app->alias(ClientContract::class, Client::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ragflow.php' => config_path('ragflow.php'),
            ]);

            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            Client::class,
            ClientContract::class,
            'ragflow',
        ];
    }
}
