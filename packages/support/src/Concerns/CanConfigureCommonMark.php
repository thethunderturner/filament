<?php

namespace Filament\Support\Concerns;

use Closure;

trait CanConfigureCommonMark
{
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
