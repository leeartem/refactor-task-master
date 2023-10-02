<?php

namespace App\Providers;

use App\Domain\Entities\User\IUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = true;

    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    public function provides(): array
    {
        return [
            IUserRepository::class
        ];
    }
}
