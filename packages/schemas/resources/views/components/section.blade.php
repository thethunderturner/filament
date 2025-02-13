@php
    $afterHeader = $getChildComponentContainer($schemaComponent::AFTER_HEADER_CONTAINER)?->toHtmlString();
    $isAside = $isAside();
    $isCollapsed = $isCollapsed();
    $isCollapsible = $isCollapsible();
    $isCompact = $isCompact();
    $isContained = $isContained();
    $isDivided = $isDivided();
    $isFormBefore = $isFormBefore();
    $description = $getDescription();
    $footer = $getChildComponentContainer($schemaComponent::FOOTER_CONTAINER)?->toHtmlString();
    $heading = $getHeading();
    $headingTag = $getHeadingTag();
    $icon = $getIcon();
    $iconColor = $getIconColor();
    $iconSize = $getIconSize();
    $shouldPersistCollapsed = $shouldPersistCollapsed();
    $isSecondary = $isSecondary();
@endphp

<div
    {{
        $attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class(['fi-sc-section'])
    }}
>
    @if (filled($label = $getLabel()))
        <div class="fi-sc-section-label-ctn">
            {{ $getChildComponentContainer($schemaComponent::BEFORE_LABEL_CONTAINER) }}

            <div class="fi-sc-section-label">
                {{ $label }}
            </div>

            {{ $getChildComponentContainer($schemaComponent::AFTER_LABEL_CONTAINER) }}
        </div>
    @endif

    @if ($aboveContentContainer = $getChildComponentContainer($schemaComponent::ABOVE_CONTENT_CONTAINER)?->toHtmlString())
        {{ $aboveContentContainer }}
    @endif

    <x-filament::section
        :after-header="$afterHeader"
        :aside="$isAside"
        :collapsed="$isCollapsed"
        :collapsible="$isCollapsible && (! $isAside)"
        :compact="$isCompact"
        :contained="$isContained"
        :content-before="$isFormBefore"
        :description="$description"
        :divided="$isDivided"
        :footer="$footer"
        :has-content-el="false"
        :heading="$heading"
        :heading-tag="$headingTag"
        :icon="$icon"
        :icon-color="$iconColor"
        :icon-size="$iconSize"
        :persist-collapsed="$shouldPersistCollapsed"
        :secondary="$isSecondary"
    >
        {{ $getChildComponentContainer()->gap(! $isDivided)->extraAttributes(['class' => 'fi-section-content']) }}
    </x-filament::section>

    @if ($belowContentContainer = $getChildComponentContainer($schemaComponent::BELOW_CONTENT_CONTAINER)?->toHtmlString())
        {{ $belowContentContainer }}
    @endif
</div>
