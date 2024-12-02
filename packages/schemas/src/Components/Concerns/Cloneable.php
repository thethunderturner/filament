<?php

namespace Filament\Schemas\Components\Concerns;

use Filament\Schemas\Components\Component;

trait Cloneable
{
    protected function cloneChildComponents(): static
    {
        if (is_array($this->childComponents)) {
            $this->childComponents = array_map(
                fn (Component $component): Component => $component->getClone(),
                $this->childComponents,
            );
        }

        return $this;
    }

    public function getClone(): static
    {
        $clone = clone $this;
        $clone->flushCachedAbsoluteKey();
        $clone->flushCachedAbsoluteStatePath();
        $clone->flushCachedInheritanceKey();
        $clone->cloneChildComponents();

        return $clone;
    }
}
