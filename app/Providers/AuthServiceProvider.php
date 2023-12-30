<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Auth\Grants\OtpGrant;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        app(AuthorizationServer::class)->enableGrantType(
            $this->makeOtpGrant(),
            Passport::tokensExpireIn()
        );
    }

    protected function makeOtpGrant()
    {
        $grant = new OtpGrant(
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
