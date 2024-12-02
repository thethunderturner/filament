@php
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\MaxWidth;
    use Filament\Support\Facades\FilamentView;
@endphp

@props([
    'alignment' => Alignment::Start,
    'ariaLabelledby' => null,
    'autofocus' => \Filament\Support\View\Components\Modal::$isAutofocused,
    'closeButton' => \Filament\Support\View\Components\Modal::$hasCloseButton,
    'closeByClickingAway' => \Filament\Support\View\Components\Modal::$isClosedByClickingAway,
    'closeByEscaping' => \Filament\Support\View\Components\Modal::$isClosedByEscaping,
    'closeEventName' => 'close-modal',
    'closeQuietlyEventName' => 'close-modal-quietly',
    'description' => null,
    'extraModalWindowAttributeBag' => null,
    'footer' => null,
    'footerActions' => [],
    'footerActionsAlignment' => Alignment::Start,
    'header' => null,
    'heading' => null,
    'icon' => null,
    'iconAlias' => null,
    'iconColor' => 'primary',
    'id' => null,
    'openEventName' => 'open-modal',
    'slideOver' => false,
    'stickyFooter' => false,
    'stickyHeader' => false,
    'trigger' => null,
    'visible' => true,
    'width' => 'sm',
])

@php
    $hasContent = ! \Filament\Support\is_slot_empty($slot);
    $hasDescription = filled($description);
    $hasFooter = (! \Filament\Support\is_slot_empty($footer)) || (is_array($footerActions) && count($footerActions)) || (! is_array($footerActions) && (! \Filament\Support\is_slot_empty($footerActions)));
    $hasHeading = filled($heading);
    $hasIcon = filled($icon);

    if (! $alignment instanceof Alignment) {
        $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
    }

    if (! $footerActionsAlignment instanceof Alignment) {
        $footerActionsAlignment = filled($footerActionsAlignment) ? (Alignment::tryFrom($footerActionsAlignment) ?? $footerActionsAlignment) : null;
    }

    if (! $width instanceof MaxWidth) {
        $width = filled($width) ? (MaxWidth::tryFrom($width) ?? $width) : null;
    }

    $closeEventHandler = filled($id) ? '$dispatch(' . \Illuminate\Support\Js::from($closeEventName) . ', { id: ' . \Illuminate\Support\Js::from($id) . ' })' : 'close()';
@endphp

<div
    @if ($ariaLabelledby)
        aria-labelledby="{{ $ariaLabelledby }}"
    @elseif ($heading)
        aria-labelledby="{{ "{$id}.heading" }}"
    @endif
    aria-modal="true"
    id="{{ $id }}"
    role="dialog"
    x-data="filamentModal({
                id: @js($id),
            })"
    @if ($id)
        x-on:{{ $closeEventName }}.window="if (($event.detail.id === @js($id)) && isOpen) close()"
        x-on:{{ $closeQuietlyEventName }}.window="if (($event.detail.id === @js($id)) && isOpen) closeQuietly()"
        x-on:{{ $openEventName }}.window="if (($event.detail.id === @js($id)) && (! isOpen)) open()"
    @endif
    x-bind:class="{
        'fi-modal-open': isOpen,
    }"
    @class([
        'fi-modal',
        'fi-modal-slide-over' => $slideOver,
        'fi-width-screen' => $width === MaxWidth::Screen,
    ])
>
    @if ($trigger)
        <div
            x-on:click="open"
            {{ $trigger->attributes->class(['fi-modal-trigger']) }}
        >
            {{ $trigger }}
        </div>
    @endif

    <div
        x-cloak
        x-show="isOpen"
        x-trap.noscroll{{ $autofocus ? '' : '.noautofocus' }}="isOpen"
    >
        <div
            aria-hidden="true"
            x-show="isOpen"
            x-transition.duration.300ms.opacity
            class="fi-modal-close-overlay"
        ></div>

        <div
            @class([
                'fi-modal-overlay',
                'fi-clickable' => $closeByClickingAway,
            ])
        >
            <div
                x-ref="modalContainer"
                @if ($closeByClickingAway)
                    {{-- Ensure that the click element is not triggered from a user selecting text inside an input. --}}
                    x-on:click.self="
                        document.activeElement.selectionStart === undefined &&
                            document.activeElement.selectionEnd === undefined &&
                            {{ $closeEventHandler }}
                    "
                @endif
                {{ $attributes->class(['fi-modal-window-ctn']) }}
            >
                <div
                    x-data="{ isShown: false }"
                    x-init="
                        $nextTick(() => {
                            isShown = isOpen
                            $watch('isOpen', () => (isShown = isOpen))
                        })
                    "
                    @if ($closeByEscaping)
                        x-on:keydown.window.escape="{{ $closeEventHandler }}"
                    @endif
                    x-show="isShown"
                    x-transition:enter="fi-transition-enter"
                    x-transition:leave="fi-transition-leave"
                    @if ($width !== MaxWidth::Screen)
                        x-transition:enter-start="fi-transition-enter-start"
                        x-transition:enter-end="fi-transition-enter-end"
                        x-transition:leave-start="fi-transition-leave-start"
                        x-transition:leave-end="fi-transition-leave-end"
                    @endif
                    {{
                        ($extraModalWindowAttributeBag ?? new \Illuminate\View\ComponentAttributeBag)->class([
                            'fi-modal-window',
                            'fi-modal-window-has-content' => $hasContent,
                            'fi-modal-window-has-footer' => $hasFooter,
                            'fi-modal-window-has-icon' => $hasIcon,
                            'fi-modal-window-has-sticky-header' => $stickyHeader,
                            'fi-hidden' => ! $visible,
                            ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : null,
                            ($width instanceof MaxWidth) ? "fi-width-{$width->value}" : (is_string($width) ? $width : null),
                        ])
                    }}
                >
                    @if ($heading || $header)
                        <div
                            @class([
                                'fi-modal-header',
                                'fi-sticky' => $stickyHeader,
                                'fi-vertical-align-center' => $hasIcon && $hasHeading && (! $hasDescription) && in_array($alignment, [Alignment::Start, Alignment::Left]),
                            ])
                        >
                            @if ($closeButton)
                                <x-filament::icon-button
                                    color="gray"
                                    icon="heroicon-o-x-mark"
                                    icon-alias="modal.close-button"
                                    icon-size="lg"
                                    :label="__('filament::components/modal.actions.close.label')"
                                    tabindex="-1"
                                    :x-on:click="$closeEventHandler"
                                    class="fi-modal-close-btn"
                                />
                            @endif

                            @if ($header)
                                {{ $header }}
                            @else
                                @if ($hasIcon)
                                    <div class="fi-modal-icon-wrp-ctn">
                                        <div
                                            @class([
                                                'fi-modal-icon-wrp',
                                                match ($iconColor) {
                                                    'gray' => null,
                                                    default => 'fi-color-custom',
                                                },
                                                is_string($iconColor) ? "fi-color-{$iconColor}" : null,
                                            ])
                                            @style([
                                                \Filament\Support\get_color_css_variables(
                                                    $iconColor,
                                                    shades: [100, 400, 500, 600],
                                                    alias: 'modal.icon',
                                                ) => $iconColor !== 'gray',
                                            ])
                                        >
                                            {{ \Filament\Support\generate_icon_html($icon, $iconAlias, (new \Illuminate\View\ComponentAttributeBag)->class(['fi-modal-icon'])) }}
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <h2 class="fi-modal-heading">
                                        {{ $heading }}
                                    </h2>

                                    @if ($hasDescription)
                                        <p class="fi-modal-description">
                                            {{ $description }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($hasContent)
                        <div class="fi-modal-content">
                            {{ $slot }}
                        </div>
                    @endif

                    @if ($hasFooter)
                        <div
                            @class([
                                'fi-modal-footer',
                                'fi-sticky' => $stickyFooter,
                                ($footerActionsAlignment instanceof Alignment) ? "fi-align-{$footerActionsAlignment->value}" : null,
                            ])
                        >
                            @if (! \Filament\Support\is_slot_empty($footer))
                                {{ $footer }}
                            @else
                                <div class="fi-modal-footer-actions">
                                    @if (is_array($footerActions))
                                        @foreach ($footerActions as $action)
                                            {{ $action }}
                                        @endforeach
                                    @else
                                        {{ $footerActions }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
