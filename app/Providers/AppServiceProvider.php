<?php

namespace App\Providers;

use App\Models\Cliente;
use App\Services\WordPressMailer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WordPressMailer::class);
    }

    public function boot(): void
    {
        $appUrl = config('app.url');

        if (!is_string($appUrl) || $appUrl === '') {
            return;
        }

        URL::forceRootUrl(rtrim($appUrl, '/'));

        $scheme = parse_url($appUrl, PHP_URL_SCHEME);

        if (is_string($scheme) && in_array($scheme, ['http', 'https'], true)) {
            URL::forceScheme($scheme);
        }

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            if ($notifiable instanceof Cliente) {
                return route('cliente.reset-password', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]);
            }
            return route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });
    }
}
