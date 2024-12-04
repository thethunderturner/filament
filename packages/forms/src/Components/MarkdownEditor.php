<?php

namespace Filament\Forms\Components;

use Closure;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class MarkdownEditor extends Field implements Contracts\CanBeLengthConstrained, Contracts\HasFileAttachments
{
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasFileAttachments;
    use Concerns\HasMaxHeight;
    use Concerns\HasMinHeight;
    use Concerns\HasPlaceholder;
    use Concerns\InteractsWithToolbarButtons;
    use HasExtraAlpineAttributes;

    /**
     * @var view-string
     */
    protected string $view = 'filament-forms::components.markdown-editor';

    /**
     * @var array<string>
     */
    protected array | Closure $toolbarButtons = [
        'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        'codeBlock',
        'heading',
        'italic',
        'link',
        'orderedList',
        'redo',
        'strike',
        'table',
        'undo',
    ];

    protected array | Closure | null $commonMarkOptions = null;

    protected array | Closure | null $commonMarkExtensions = null;

    public function commonMarkOptions(array | Closure | null $commonMarkOptions): static
    {
        $this->commonMarkOptions = $commonMarkOptions;

        return $this;
    }

    public function getCommonMarkOptions(): ?array
    {
        return $this->evaluate($this->commonMarkOptions);
    }

    public function commonMarkExtensions(array | Closure | null $commonMarkExtensions): static
    {
        $this->commonMarkExtensions = $commonMarkExtensions;

        return $this;
    }

    public function getCommonMarkExtensions(): ?array
    {
        return $this->evaluate($this->commonMarkExtensions);
    }
}
