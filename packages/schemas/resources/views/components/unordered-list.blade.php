<ul
    @class([
        'fi-sc-unordered-list',
        (($size = $getSize()) instanceof \Filament\Support\Enums\TextSize) ? "fi-size-{$size->value}" : $size,
    ])
>
    @foreach ($getChildComponentContainer()->getComponents() as $component)
        <li>
            {{ $component }}
        </li>
    @endforeach
</ul>
