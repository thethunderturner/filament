<?php

namespace Filament\Tests\Fixtures\Resources\PostCategories\Pages;

use App\Models\Blog\Post;
use App\Models\Shop\Category;
use Filament\Actions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Tests\Fixtures\Resources\PostCategories\PostCategoryResource;
use Illuminate\Support\Str;

class ListPostCategories extends ListRecords
{
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
