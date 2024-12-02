<?php

namespace Filament\Tests\Panels\Fixtures\Resources\Users\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tests\Panels\Fixtures\Resources\Users\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
