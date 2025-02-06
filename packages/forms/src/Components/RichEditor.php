<?php

namespace Filament\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\StateCasts\RichEditorStateCast;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Livewire\Component;

class RichEditor extends Field implements Contracts\CanBeLengthConstrained, Contracts\HasFileAttachments
{
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasFileAttachments;
    use Concerns\HasPlaceholder;
    use Concerns\InteractsWithToolbarButtons;
    use HasExtraAlpineAttributes;

    /**
     * @var view-string
     */
    protected string $view = 'filament-forms::components.rich-editor';

    /**
     * @var array<string>
     */
    protected array | Closure $toolbarButtons = [
        'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        'codeBlock',
        'h2',
        'h3',
        'italic',
        'link',
        'orderedList',
        'redo',
        'strike',
        'sub',
        'sup',
        'underline',
        'undo',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerActions([
            Action::make('link')
                ->form([
                    TextInput::make('href')
                        ->label('URL')
                        ->url(),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component) {
                    $component->runCommand(
                        name: 'toggleLink',
                        options: [
                            'href' => $data['href'],
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );

                    Notification::make()
                        ->title('Link Added')
                        ->success()
                        ->send();
                }),
        ]);
    }

    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        return [
            ...parent::getDefaultStateCasts(),
            app(RichEditorStateCast::class, ['richEditor' => $this]),
        ];
    }

    public function runCommand(string $name, array $options, array $editorSelection): void
    {
        $key = $this->getKey();
        $livewire = $this->getLivewire();

        $livewire->dispatch(
            'run-rich-editor-command',
            awaitSchemaComponent: $key,
            livewireId: $livewire->getId(),
            key: $key,
            editorSelection: $editorSelection,
            name: $name,
            options: $options,
        );
    }
}
