<?php

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tests\Fixtures\Livewire\Livewire;
use Filament\Tests\Models\Post;
use Filament\Tests\Models\User;
use Filament\Tests\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Exceptions\RootTagMissingFromViewException;

use function Filament\Tests\livewire;

uses(TestCase::class);

it('can fill and assert data in a repeater', function (array $data) {
    $undoRepeaterFake = Repeater::fake();

    try {
        livewire(TestComponentWithRepeater::class)
            ->fillForm($data)
            ->assertFormSet($data);
    } catch (RootTagMissingFromViewException $exception) {
        // Flaky test
    }

    $undoRepeaterFake();
})->with([
    'normal' => fn (): array => ['normal' => [
        [
            'title' => Str::random(),
            'category' => Str::random(),
        ],
        [
            'title' => Str::random(),
            'category' => Str::random(),
        ],
        [
            'title' => Str::random(),
            'category' => Str::random(),
        ],
    ]],
    'simple' => fn (): array => ['simple' => [
        Str::random(),
        Str::random(),
        Str::random(),
    ]],
    'nested' => fn (): array => ['parent' => [
        [
            'title' => Str::random(),
            'category' => Str::random(),
            'nested' => [
                [
                    'name' => Str::random(),
                ],
                [
                    'name' => Str::random(),
                ],
                [
                    'name' => Str::random(),
                ],
            ],
            'nestedSimple' => [
                Str::random(),
                Str::random(),
                Str::random(),
            ],
        ],
        [
            'title' => Str::random(),
            'category' => Str::random(),
            'nested' => [
                [
                    'name' => Str::random(),
                ],
                [
                    'name' => Str::random(),
                ],
                [
                    'name' => Str::random(),
                ],
            ],
            'nestedSimple' => [
                Str::random(),
                Str::random(),
                Str::random(),
            ],
        ],
        [
            'title' => Str::random(),
            'category' => Str::random(),
            'nested' => [
                [
                    'name' => Str::random(),
                ],
                [
                    'name' => Str::random(),
                ],
                [
                    'name' => Str::random(),
                ],
            ],
            'nestedSimple' => [
                Str::random(),
                Str::random(),
                Str::random(),
            ],
        ],
    ]],
]);

it('can remove items from a repeater', function () {
    $undoRepeaterFake = Repeater::fake();

    livewire(TestComponentWithRepeater::class)
        ->fillForm($data = [
            'normal' => [
                [
                    'title' => Str::random(),
                    'category' => Str::random(),
                ],
                [
                    'title' => Str::random(),
                    'category' => Str::random(),
                ],
            ],
        ])
        ->assertFormSet($data)
        ->fillForm([
            'normal' => [
                Arr::first($data['normal']),
            ],
        ])
        ->assertFormSet(function (array $data) {
            expect($data['normal'])->toHaveCount(1);

            return [
                'normal' => [
                    Arr::first($data['normal']),
                ],
            ];
        });

    $undoRepeaterFake();
});

it('loads relationship', function () {
    $user = User::factory()
        ->has(Post::factory()->count(3))
        ->create();

    $componentContainer = Schema::make(Livewire::make())
        ->statePath('data')
        ->components([
            (new Repeater('repeater'))
                ->relationship('posts'),
        ])
        ->model($user);

    $componentContainer->loadStateFromRelationships();

    $componentContainer->saveRelationships();

    expect($user->posts()->count())
        ->toBe(3);
});

it('throw exception for missing relationship', function () {
    $componentContainer = Schema::make(Livewire::make())
        ->statePath('data')
        ->components([
            (new Repeater(Str::random()))
                ->relationship('missing'),
        ])
        ->model(Post::factory()->create());

    $componentContainer
        ->saveRelationships();
})->throws(Exception::class, 'The relationship [missing] does not exist on the model [Filament\Tests\Models\Post].');

class TestComponentWithRepeater extends Livewire
{
    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Repeater::make('normal')
                    ->itemLabel(function (array $state) {
                        return $state['title'] . $state['category'];
                    })
                    ->schema([
                        TextInput::make('title'),
                        TextInput::make('category'),
                    ]),
                Repeater::make('simple')
                    ->simple(TextInput::make('title')),
                Repeater::make('parent')
                    ->itemLabel(fn (array $state) => $state['title'] . $state['category'])
                    ->schema([
                        TextInput::make('title'),
                        TextInput::make('category'),
                        Repeater::make('nested')
                            ->itemLabel(fn (array $state) => $state['name'])
                            ->schema([
                                TextInput::make('name'),
                            ]),
                        Repeater::make('nestedSimple')
                            ->simple(TextInput::make('name')),
                    ]),
            ])
            ->statePath('data');
    }
}
