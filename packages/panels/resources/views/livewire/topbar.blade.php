<div class="fi-topbar-ctn">
    @php
        $navigation = filament()->getNavigation();
    @endphp

    <x-filament-panels::topbar :navigation="$navigation" />

    <x-filament-actions::modals />
</div>
