---
title: Slider
---
import AutoScreenshot from "@components/AutoScreenshot.astro"

## Overview

The slider component allows you to select a value from a range of values. The component uses the [noUiSlider library](https://refreshless.com/nouislider/).

<AutoScreenshot name="forms/fields/slider/simple" alt="Slider" version="4.x" />


## Changing the range

By default, the slider has a range of 0 to 100. You can change using the `range()` method:

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(['min' => 0, 'max' => 300]),
```

## Changing the step

You can add steps to the slider, where every its dragged, it will "jump" to the next step. This can enable using the `step()` method:

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->step(true),
```

## Pips

Pips allow the user to see the in between values of the sliders, which can be enabled by injecting Javascript code into the slider component:

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->pips(RawJs::make(<<<'JS'
        {
            mode: 'steps',
            stepped: true,
            density: 5,
        }
    JS))
    ->step(true)
]);
```

<AutoScreenshot name="forms/fields/slider/pips" alt="Slider" version="4.x" />

## Multiple handles

You can add multiple handles to your slider by setting multiple values in the `start()` method, where each values represents the staring point of each handle:

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->connect(true)
    ->pips(RawJs::make(<<<'JS'
        {
            mode: 'steps',
            stepped: true,
            density: 5,
        }
    JS))
    ->start([10, 50])
    ->range(['min' => 0, 'max' => 200]),
```

<AutoScreenshot name="forms/fields/slider/increased" alt="Slider" version="4.x" />

## User Interaction

The slider component offers several ways to handle user interaction.

The slider handle can be `tapped`, `dragged`, `fixed` etc. The slider behaviour is defined by `SliderBehaviour::Drag`,`SliderBehaviour::DragAll`,`SliderBehaviour::Tap`,`SliderBehaviour::Fixed`,`SliderBehaviour::Snap`,`SliderBehaviour::Unconstrained`,`SliderBehaviour::InvertConnects`,`SliderBehaviour::None`
```php
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Enums\SliderBehaviour;

Slider::make('slider')
    ->behaviour(SliderBehaviour::Drag),
```

The orientation of the slider can be set to `horizontal` or `vertical`:
```php
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Enums\SliderOrientation;

Slider::make('slider')
    ->orientation(SliderOrientation::Drag),
```

The direction of the slider can be set to `ltr` or `rtl`:
```php
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Enums\SliderDirection;

Slider::make('slider')
    ->direction(SliderDirection::LTR),
```

## Limit

The `limit()` method sets the maximum distance between the handles:

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->connect(true)
    ->step(true)
    ->start([10, 50])
    ->limit(90)
    ->range(['min' => 0, 'max' => 200]),
```
In order to use this method you need at least 2 handles.

## Margin

The `margin()` method sets the maximum distance between the handles:
```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->connect(true)
    ->step(true)
    ->start([10, 50])
    ->margin(90)
    ->range(['min' => 0, 'max' => 200]),
```

## Padding

The `padding()` method limits how close to the slider edges handles can be.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->start([20, 80])
    ->padding([10,15])
    ->range(['min' => 0, 'max' => 100]),
```

## Tooltips

You can add and even format tooltips to the slider handles:

<AutoScreenshot name="forms/fields/slider/tooltips" alt="Slider" version="4.x" />

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->connect(true)
    ->tooltips(true)
    ->format(RawJs::make(<<<'JS'
        wNumb({decimals: 1})
    JS))
    ->start([50, 150])
    ->range(['min' => 0, 'max' => 200]),
```

If you would like to use Aria format for the tooltips, you can use the `ariaFormat()` method:

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->connect(true)
    ->tooltips(true)
    ->ariaFormat(RawJs::make(<<<'JS'
        wNumb({decimals: 3})
    JS))
    ->start([50, 150])
    ->range(['min' => 0, 'max' => 200]),
```

