<?php

namespace App\Livewire\Panels\Navigation;

use Filament\Pages\Page;
use Filament\Navigation\NavigationItem;

class ChangeIcon extends Page
{
    protected static string $view = 'livewire.panels.navigation.empty';

    public function mount()
    {
        filament()
            ->getCurrentPanel()
            ->navigationItems([
                NavigationItem::make()
                    ->label('Settings')
                    ->url(fn (): string => '#')
                    ->icon('heroicon-o-document-text'),
            ]);
    }
}
