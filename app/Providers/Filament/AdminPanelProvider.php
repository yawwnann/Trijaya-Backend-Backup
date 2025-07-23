<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel as FilamentPanel;
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
    /**
     * Daftarkan panel admin Filament.
     *
     * @param FilamentPanel $panel
     * @return FilamentPanel
     */
    public function panel(FilamentPanel $panel): FilamentPanel
    {
        return $panel
            ->default()
            ->brandName('Trijaya Agung Admin')
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
            ])
            // Menemukan sumber daya (Resources) di direktori App\Filament\Resources
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // Menemukan halaman (Pages) di direktori App\Filament\Pages
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([

            ])
            // Menemukan widget (Widgets) di direktori App\Filament\Widgets
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
            ])
            // ->scripts([
            //     'https://cdn.jsdelivr.net/npm/chart.js'
            // ])
            ->middleware([
                EncryptCookies::class, // Mengenkripsi cookie HTTP
                AddQueuedCookiesToResponse::class, // Menambahkan cookie ke antrean respons
                StartSession::class, // Memulai sesi HTTP
                AuthenticateSession::class, // Mengautentikasi sesi pengguna
                ShareErrorsFromSession::class, // Berbagi kesalahan validasi dari sesi
                VerifyCsrfToken::class, // Memverifikasi token CSRF untuk permintaan POST
                SubstituteBindings::class, // Mengganti binding model di rute
                DisableBladeIconComponents::class, // Menonaktifkan komponen ikon Blade
                DispatchServingFilamentEvent::class, // Mengeluarkan event saat Filament sedang disajikan
            ])
        ;
    }
}

