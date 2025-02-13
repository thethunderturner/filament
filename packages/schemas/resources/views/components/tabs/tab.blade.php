@php
    $id = $getId();
    $key = $getKey(isAbsolute: false);
    $tabs = $getContainer()->getParentComponent();
    $isContained = $tabs->isContained();
    $livewireProperty = $tabs->getLivewireProperty();

    $childComponentContainer = $getChildComponentContainer();
@endphp

@if (! empty($childComponentContainer->getComponents()))
    @if (blank($livewireProperty))
        <div
            x-bind:class="{
                'fi-active': tab === @js($key),
            }"
            x-on:expand="tab = @js($key)"
            {{
                $attributes
                    ->merge([
                        'aria-labelledby' => $id,
                        'id' => $id,
                        'role' => 'tabpanel',
                        'tabindex' => '0',
                        'wire:key' => $getLivewireKey() . '.container',
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->class(['fi-sc-tabs-tab'])
            }}
        >
            {{ $childComponentContainer }}
        </div>
    @elseif (strval($this->{$livewireProperty}) === strval($key))
        <div
            {{
                $attributes
                    ->merge([
                        'aria-labelledby' => $id,
                        'id' => $id,
                        'role' => 'tabpanel',
                        'tabindex' => '0',
                        'wire:key' => $getLivewireKey() . '.container',
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->class(['fi-sc-tabs-tab fi-active'])
            }}
        >
            {{ $childComponentContainer }}
        </div>
    @endif
@endif
