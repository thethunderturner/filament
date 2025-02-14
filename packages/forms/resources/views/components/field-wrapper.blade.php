@php
    use Filament\Support\Enums\VerticalAlignment;
@endphp

@props([
    'field' => null,
    'hasInlineLabel' => null,
    'hasNestedRecursiveValidationRules' => null,
    'id' => null,
    'inlineLabelVerticalAlignment' => VerticalAlignment::Start,
    'isDisabled' => null,
    'label' => null,
    'labelPrefix' => null,
    'labelSrOnly' => null,
    'labelSuffix' => null,
    'required' => null,
    'statePath' => null,
])

@php
    if ($field) {
        $hasInlineLabel ??= $field->hasInlineLabel();
        $hasNestedRecursiveValidationRules ??= $field instanceof \Filament\Forms\Components\Contracts\HasNestedRecursiveValidationRules;
        $id ??= $field->getId();
        $isDisabled ??= $field->isDisabled();
        $label ??= $field->getLabel();
        $labelSrOnly ??= $field->isLabelHidden();
        $required ??= $field->isMarkedAsRequired();
        $statePath ??= $field->getStatePath();
    }

    $beforeLabelContainer = $field?->getChildComponentContainer($field::BEFORE_LABEL_CONTAINER)?->toHtmlString();
    $afterLabelContainer = $field?->getChildComponentContainer($field::AFTER_LABEL_CONTAINER)?->toHtmlString();
    $aboveContentContainer = $field?->getChildComponentContainer($field::ABOVE_CONTENT_CONTAINER)?->toHtmlString();
    $belowContentContainer = $field?->getChildComponentContainer($field::BELOW_CONTENT_CONTAINER)?->toHtmlString();
    $beforeContentContainer = $field?->getChildComponentContainer($field::BEFORE_CONTENT_CONTAINER)?->toHtmlString();
    $afterContentContainer = $field?->getChildComponentContainer($field::AFTER_CONTENT_CONTAINER)?->toHtmlString();
    $aboveErrorMessageContainer = $field?->getChildComponentContainer($field::ABOVE_ERROR_MESSAGE_CONTAINER)?->toHtmlString();
    $belowErrorMessageContainer = $field?->getChildComponentContainer($field::BELOW_ERROR_MESSAGE_CONTAINER)?->toHtmlString();

    $hasError = filled($statePath) && ($errors->has($statePath) || ($hasNestedRecursiveValidationRules && $errors->has("{$statePath}.*")));
@endphp

<div
    data-field-wrapper
    {{
        $attributes
            ->merge($field?->getExtraFieldWrapperAttributes() ?? [], escape: false)
            ->class([
                'fi-fo-field',
                'fi-fo-field-has-inline-label' => $hasInlineLabel,
            ])
    }}
>
    @if ($label && $labelSrOnly)
        <label for="{{ $id }}" class="fi-fo-field-label fi-hidden">
            {{ $label }}
        </label>
    @endif

    <div
        @class([
            'fi-fo-field-label-col',
            "fi-vertical-align-{$inlineLabelVerticalAlignment->value}" => $hasInlineLabel,
        ])
    >
        {{ $field?->getChildComponentContainer($field::ABOVE_LABEL_CONTAINER) }}

        @if (($label && (! $labelSrOnly)) || $labelPrefix || $labelSuffix || $beforeLabelContainer || $afterLabelContainer)
            <div
                @class([
                    'fi-fo-field-label-ctn',
                    ($label instanceof \Illuminate\View\ComponentSlot) ? $label->attributes->get('class') : null,
                ])
            >
                {{ $beforeLabelContainer }}

                @if ($label && (! $labelSrOnly))
                    <label class="fi-fo-field-label">
                        {{ $labelPrefix }}

                        {{-- Deliberately poor formatting to ensure that the asterisk sticks to the final word in the label. --}}
                        {{ $label }}@if ($required && (! $isDisabled))<sup class="fi-fo-field-label-required-mark">*</sup>
                        @endif

                        {{ $labelSuffix }}
                    </label>
                @elseif ($labelPrefix)
                    {{ $labelPrefix }}
                @elseif ($labelSuffix)
                    {{ $labelSuffix }}
                @endif

                {{ $afterLabelContainer }}
            </div>
        @endif

        {{ $field?->getChildComponentContainer($field::BELOW_LABEL_CONTAINER) }}

        @if ((! \Filament\Support\is_slot_empty($slot)) || $hasError || $aboveContentContainer || $belowContentContainer || $beforeContentContainer || $afterContentContainer || $aboveErrorMessageContainer || $belowErrorMessageContainer)
            <div class="fi-fo-field-content-col">
                {{ $aboveContentContainer }}

                @if ($beforeContentContainer || $afterContentContainer)
                    <div class="fi-fo-field-content-ctn">
                        {{ $beforeContentContainer }}

                        <div class="fi-fo-field-content">
                            {{ $slot }}
                        </div>

                        {{ $afterContentContainer }}
                    </div>
                @else
                    {{ $slot }}
                @endif

                {{ $belowContentContainer }}

                {{ $aboveErrorMessageContainer }}

                @if ($hasError)
                    <p
                        data-validation-error
                        class="fi-fo-field-wrp-error-message"
                    >
                        {{ $errors->has($statePath) ? $errors->first($statePath) : ($hasNestedRecursiveValidationRules ? $errors->first("{$statePath}.*") : null) }}
                    </p>
                @endif

                {{ $belowErrorMessageContainer }}
            </div>
        @endif
    </div>
</div>
