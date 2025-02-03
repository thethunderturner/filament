<?php

namespace App\Livewire\Panels\Navigation;

use Filament\Pages\Page;
use Filament\Navigation\NavigationItem;

class ActiveIcon extends Page
{
    protected static string $view = 'livewire.panels.navigation.empty';

    public function mount()
    {
        filament()
            ->getCurrentPanel()
            ->navigationItems([
                NavigationItem::make()
                    ->label('Settings Inactive')
                    ->url(fn (): string => '#')
                    ->activeIcon('heroicon-o-document-text')
                    ->icon('heroicon-o-cog'),
                NavigationItem::make('')
                    ->label('Settings Active')
                    ->url(fn (): string => '#')
                    ->isActiveWhen(fn() => request()->path() === 'panels/navigation/active-icon')
                    ->activeIcon('heroicon-s-cog')
                    ->icon('heroicon-s-cog'),
            ]);
    }
}
