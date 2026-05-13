<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('SIPDHB')
            ->renderHook(
                'panels::auth.login.form.before',
                fn() => new \Illuminate\Support\HtmlString('
        <style>
            .fi-logo { display: none !important; }
            .fi-simple-header { display: none !important; }
        </style>
        <div style="text-align: center;">
    <span style="font-size: 2.1rem; font-weight: 700; color: #16a34a;">
        SIPDHB
    </span>
    <br>
    <span style="font-size: 0.7rem; color: #6b7280;">
        Sistem Informasi Pengolahan Data Hasil Bumi Kecamatan Gane Barat
    </span>
    <hr style="border: 1px solid #e5e7eb; margin-top: 0.5rem; margin-bottom: 0.5rem;">
</div>
    ')
            )
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\ProduksiChart::class,
                \App\Filament\Widgets\PetaniProduksiChart::class,
                \App\Filament\Widgets\PetaniStatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
