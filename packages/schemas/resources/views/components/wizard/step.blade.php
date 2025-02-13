@php
    $id = $getId();
    $key = $getKey();
    $isContained = $getContainer()->getParentComponent()->isContained();
@endphp

<div
    x-bind:tabindex="$el.querySelector('[autofocus]') ? '-1' : '0'"
    x-bind:class="{
        'fi-active': step === @js($key),
    }"
    x-on:expand="
        if (! isStepAccessible(@js($key))) {
            return
        }

        step = @js($key)
    "
    x-ref="step-{{ $key }}"
    {{
        $attributes
            ->merge([
                'aria-labelledby' => $id,
                'id' => $id,
                'role' => 'tabpanel',
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-sc-wizard-step'])
    }}
>
    {{ $getChildComponentContainer() }}
</div>
