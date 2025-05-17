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
use Schema;
use Storage;
use App\Models\SiteSetting;
use RyanChandler\FilamentNavigation\FilamentNavigation;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Tablo varlık kontrolü ekleyelim
        if (Schema::hasTable('site_settings')) {
            $siteSetting = SiteSetting::first() ?? null;
            $favicon = isset($siteSetting->fav_icon) ? Storage::url($siteSetting->fav_icon) : '';
            $brand_name = $siteSetting->data['name'] ?? config('app.name');
            $brand_logo = isset($siteSetting->logo) ? Storage::url($siteSetting->footer_logo) : '';
        } else {
            $favicon = '';
            $brand_name = config('app.name');
            $brand_logo = '';
        }

        return $panel
            ->default()
            ->id('admin')
            ->brandName($brand_name ?? '')
            ->brandLogo($brand_logo ?? '')
            ->darkModeBrandLogo($brand_logo ?? '')
            ->brandLogoHeight('3rem')
            ->path('admin')
            ->favicon($favicon)
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugins([FilamentNavigation::make(),]);
    }
}
