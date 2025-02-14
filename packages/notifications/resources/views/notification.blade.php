@php
    use Filament\Notifications\Livewire\Notifications;
    use Filament\Notifications\View\Components\NotificationComponent;
    use Filament\Notifications\View\Components\NotificationComponent\IconComponent;
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\VerticalAlignment;
    use Illuminate\Support\Arr;

    $color = $getColor() ?? 'gray';
    $isInline = $isInline();
    $status = $getStatus();
    $title = $getTitle();
    $hasTitle = filled($title);
    $date = $getDate();
    $hasDate = filled($date);
    $body = $getBody();
    $hasBody = filled($body);
@endphp

<div
    x-data="notificationComponent({ notification: @js($notification) })"
    x-transition:enter-start="fi-transition-enter-start"
    x-transition:leave-end="fi-transition-leave-end"
    {{
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:key' => "{$this->getId()}.notifications.{$notification->getId()}",
                'x-on:close-notification.window' => "if (\$event.detail.id == '{$notification->getId()}') close()",
            ], escape: false)
            ->color(NotificationComponent::class, $color)
            ->class([
                'fi-no-notification',
                'fi-inline' => $isInline,
                "fi-status-{$status}" => $status,
            ])
    }}
>
    @if ($icon = $getIcon())
        {{
            \Filament\Support\generate_icon_html(
                $icon,
                attributes: (new \Illuminate\View\ComponentAttributeBag)->color(IconComponent::class, $getIconColor())->class(['fi-no-notification-icon']),
                size: $getIconSize(),
            )
        }}
    @endif

    <div class="fi-no-notification-main">
        @if ($hasTitle || $hasDate || $hasBody)
            <div class="fi-no-notification-text">
                @if ($hasTitle)
                    <h3 class="fi-no-notification-title">
                        {{ str($title)->sanitizeHtml()->toHtmlString() }}
                    </h3>
                @endif

                @if ($hasDate)
                    <time class="fi-no-notification-date">
                        {{ $date }}
                    </time>
                @endif

                @if ($hasBody)
                    <div class="fi-no-notification-body">
                        {{ str($body)->sanitizeHtml()->toHtmlString() }}
                    </div>
                @endif
            </div>
        @endif

        @if ($actions = $getActions())
            <div class="fi-ac fi-no-notification-actions">
                @foreach ($actions as $action)
                    {{ $action }}
                @endforeach
            </div>
        @endif
    </div>

    <button
        type="button"
        x-on:click="close"
        class="fi-icon-btn fi-no-notification-close-btn"
    >
        {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::XMark, alias: 'notifications::notification.close-button') }}
    </button>
</div>
