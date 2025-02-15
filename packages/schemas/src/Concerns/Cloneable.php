<?php

namespace Filament\Schemas\Concerns;

use Closure;

trait Cloneable
{
    /**
     * @var array<Closure>
     */
    protected array $cloneCallbacks = [];

    public function afterClone(Closure $callback): static
    {
        $this->cloneCallbacks[] = $callback;

        return $this;
    }

    public function getClone(): static
    {
        $clone = clone $this;
        $clone->flushCachedAbsoluteKey();
        $clone->flushCachedAbsoluteStatePath();
        $clone->flushCachedInheritanceKey();
        $clone->cloneComponents();

        foreach ($this->cloneCallbacks as $callback) {
            $clone->evaluate(
                value: $callback->bindTo($clone),
                namedInjections: ['clone' => $clone, 'original' => $this]
            );
        }

        return $clone;
    }
}
