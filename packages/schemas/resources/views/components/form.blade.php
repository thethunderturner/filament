<form
    {{
        $attributes
            ->merge([
                'id' => $getId(),
                'wire:submit' => $getLivewireSubmitHandler(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-sc-form',
                'fi-dense' => $isDense(),
            ])
    }}
>
    {{ $getChildComponentContainer($schemaComponent::HEADER_CONTAINER) }}

    {{ $getChildComponentContainer() }}

    {{ $getChildComponentContainer($schemaComponent::FOOTER_CONTAINER) }}
</form>
