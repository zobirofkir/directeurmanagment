<?php

namespace App\Providers\Filament;

use App\Models\Document;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Auth\Events\Login;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Filament\Navigation\NavigationGroup;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //
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
            ->navigationGroups([
                'User Management',
                'Documents',
                'System'
            ])
            ->brandName('Admin Panel');
    }

    public function boot(): void
    {
        Filament::serving(function () {
            // Get authenticated user
            $user = Auth::user();

            Filament::registerRenderHook('user-menu.start', fn () =>
                view('filament.components.user-role', [
                    'role' => $user?->roles->first()?->name ?? 'Guest'
                ])
            );

            Filament::registerNavigationItems([
                NavigationItem::make()
                    ->group('User Management')
                    ->label('Users')
                    ->url(fn () => route('filament.admin.resources.users.index'))
                    ->icon('heroicon-o-users')
                    ->visible(fn () => $user && $user->hasAnyRole([
                        'Director',
                        'Secretary',
                        'SecretaryGeneral'
                    ])),

                NavigationItem::make()
                    ->group('User Management')
                    ->label('Employees')
                    ->url(fn () => route('filament.admin.resources.employees.index'))
                    ->icon('heroicon-o-user-group')
                    ->visible(fn () => $user && $user->hasAnyRole([
                        'Director',
                        'Secretary',
                        'SecretaryGeneral'
                    ])),

                NavigationItem::make()
                    ->group('Documents')
                    ->label('Documents')
                    ->badge(fn () => Document::activeCount())
                    ->url(fn () => route('filament.admin.resources.documents.index'))
                    ->icon('heroicon-o-document')
                    ->visible(fn () => $user && $user->hasAnyRole([
                        'Director',
                        'Secretary',
                        'SecretaryGeneral'
                    ])),
            ]);
        });

        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof \App\Models\User) {
                $event->user->forceFill(['is_active' => true])->save();
            }
        });
    }
}
