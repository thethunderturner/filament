@props([
    'label' => null,
    'labelHidden' => false,
    'contained' => true,
])

<fieldset
    {{
        $attributes->class([
            'fi-fieldset',
            'fi-fieldset-label-hidden' => $labelHidden,
            'fi-fieldset-not-contained' => ! $contained,
        ])
    }}
>
    @if (filled($label))
        <legend>
            {{ $label }}
        </legend>
    @endif

    {{ $slot }}
</fieldset>
