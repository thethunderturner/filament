<x-filament::fieldset
    :label="$getLabel()"
    :label-hidden="$isLabelHidden()"
    :contained="$isContained()"
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
    "
>
    {{ $getChildComponentContainer() }}
</x-filament::fieldset>
