@props([
    'label' => null,
    'labelHidden' => false,
    'isContained' => true,
])

<fieldset
    {{
        $attributes->class([
            'fi-fieldset',
            'fi-fieldset-label-hidden' => $labelHidden,
            'fi-fieldset-contained' => $isContained,
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
