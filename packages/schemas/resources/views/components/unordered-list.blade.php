<ul
    @class([
        'fi-sc-unordered-list ms-3 list-disc sm:columns-2',
        match ($size = $getSize()) { // @todo: Support TextSize enum here
            'xs' => 'text-xs',
            null => 'text-sm',
            default => $size,
        },
    ])
>
    @foreach ($getChildComponentContainer()->getComponents() as $component)
        <li>
            {{ $component }}
        </li>
    @endforeach
</ul>
