@php
    use Filament\Support\Enums\VerticalAlignment;
    use Filament\Support\Facades\FilamentView;

    $actions = $getChildComponentContainer()->getComponents();
    $alignment = $getAlignment();
    $isFullWidth = $isFullWidth();
    $verticalAlignment = $getVerticalAlignment();

    if (! $verticalAlignment instanceof VerticalAlignment) {
        $verticalAlignment = filled($verticalAlignment) ? (VerticalAlignment::tryFrom($verticalAlignment) ?? $verticalAlignment) : null;
    }
@endphp

<div
    @if ($isSticky())
        @if (FilamentView::hasSpaMode())
            {{-- format-ignore-start --}}x-load="visible || event (x-modal-opened)"{{-- format-ignore-end --}}
        @else
            x-load
        @endif
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('actions', 'filament/schemas') }}"
        x-data="actionsSchemaComponent()"
        x-on:scroll.window="evaluatePageScrollPosition"
        x-bind:class="{
            'fi-sticky': isSticky,
        }"
    @endif
    {{
        $attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-sc-actions',
                ($verticalAlignment instanceof VerticalAlignment) ? "fi-vertical-align-{$verticalAlignment->value}" : $verticalAlignment,
            ])
    }}
>
    @if (filled($label = $getLabel()))
        <div class="fi-sc-actions-label-ctn">
            {{ $getChildComponentContainer($schemaComponent::BEFORE_LABEL_CONTAINER) }}

            <div class="fi-sc-actions-label">
                {{ $label }}
            </div>

            {{ $getChildComponentContainer($schemaComponent::AFTER_LABEL_CONTAINER) }}
        </div>
    @endif

    @if ($aboveContentContainer = $getChildComponentContainer($schemaComponent::ABOVE_CONTENT_CONTAINER)?->toHtmlString())
        {{ $aboveContentContainer }}
    @endif

    <x-filament::actions
        :actions="$actions"
        :alignment="$alignment"
        :full-width="$isFullWidth"
    />

    @if ($belowContentContainer = $getChildComponentContainer($schemaComponent::BELOW_CONTENT_CONTAINER)?->toHtmlString())
        {{ $belowContentContainer }}
    @endif
</div>
