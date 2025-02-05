<?php

namespace Filament\Forms\Components\StateCasts;

use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use Tiptap\Editor;

class RichEditorStateCast implements StateCast
{
    public function __construct(
        protected RichEditor $richEditor,
    ) {}

    public function get(mixed $state): string | array
    {
        return (new Editor)
            ->setContent($state ?? [
                'type' => 'doc',
                'content' => [],
            ])
            ->getHTML();
    }

    public function set(mixed $state): array
    {
        return (new Editor)
            ->setContent($state ?? [
                'type' => 'doc',
                'content' => [],
            ])
            ->getDocument();
    }
}
