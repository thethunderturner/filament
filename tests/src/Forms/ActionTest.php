<?php

use Filament\Actions\Action;
use Filament\Actions\Testing\Fixtures\TestAction;
use Filament\Forms\Components\TextInput;
use Filament\Tests\Forms\Fixtures\Actions;
use Filament\Tests\TestCase;
use Illuminate\Support\Str;

use function Filament\Tests\livewire;

uses(TestCase::class);

it('can register actions', function () {
    $action = Action::make($actionName = Str::random());

    $component = TextInput::make('name')
        ->registerActions([$action]);

    expect($component->getAction($actionName))
        ->toBe($action);
});

it('can auto-register actions from affixes', function () {
    $component = TextInput::make('name')
        ->prefixAction(
            $prefixAction = Action::make($prefixActionName = Str::random()),
        )
        ->suffixAction(
            $suffixAction = Action::make($suffixActionName = Str::random()),
        );

    expect($component)
        ->getAction($prefixActionName)->toBe($prefixAction)
        ->getAction($suffixActionName)->toBe($suffixAction);
});

it('can call an action with data', function () {
    livewire(Actions::class)
        ->callAction(TestAction::make('setValue')->schemaComponent('form.textInput'), data: [
            'value' => $value = Str::random(),
        ])
        ->assertFormSet(['textInput' => $value]);

    livewire(Actions::class)
        ->callFormComponentAction('textInput', 'setValue', data: [
            'value' => $value = Str::random(),
        ])
        ->assertFormSet(['textInput' => $value]);
});

it('can validate an action\'s data', function () {
    livewire(Actions::class)
        ->callAction(TestAction::make('setValue')->schemaComponent('form.textInput'), data: [
            'value' => null,
        ])
        ->assertHasActionErrors(['value' => ['required']])
        ->assertFormSet(['textInput' => null]);

    livewire(Actions::class)
        ->callFormComponentAction('textInput', 'setValue', data: [
            'value' => null,
        ])
        ->assertHasFormComponentActionErrors(['value' => ['required']])
        ->assertFormSet(['textInput' => null]);
});

it('can set default action data when mounted', function () {
    livewire(Actions::class)
        ->mountAction(TestAction::make('setValue')->schemaComponent('form.textInput'))
        ->assertActionDataSet([
            'value' => 'foo',
        ]);

    livewire(Actions::class)
        ->mountFormComponentAction('textInput', 'setValue')
        ->assertFormComponentActionDataSet([
            'value' => 'foo',
        ]);
});

it('can call an action with arguments', function () {
    livewire(Actions::class)
        ->callAction(TestAction::make('setValueFromArguments')->schemaComponent('form.textInput')->arguments([
            'value' => $value = Str::random(),
        ]))
        ->assertFormSet(['textInput' => $value]);

    livewire(Actions::class)
        ->callFormComponentAction('textInput', 'setValueFromArguments', arguments: [
            'value' => $value = Str::random(),
        ])
        ->assertFormSet(['textInput' => $value]);
});

it('can call an action and halt', function () {
    livewire(Actions::class)
        ->callAction(TestAction::make('halt')->schemaComponent('form.textInput'))
        ->assertActionHalted(TestAction::make('halt')->schemaComponent('form.textInput'));

    livewire(Actions::class)
        ->callFormComponentAction('textInput', 'halt')
        ->assertFormComponentActionHalted('textInput', 'halt');
});

it('can hide an action', function () {
    livewire(Actions::class)
        ->assertActionHidden(TestAction::make('hidden')->schemaComponent('form.textInput'))
        ->assertActionVisible(TestAction::make('visible')->schemaComponent('form.textInput'));

    livewire(Actions::class)
        ->assertFormComponentActionVisible('textInput', 'visible')
        ->assertFormComponentActionHidden('textInput', 'hidden');
});

it('can disable an action', function () {
    livewire(Actions::class)
        ->assertActionDisabled(TestAction::make('disabled')->schemaComponent('form.textInput'))
        ->assertActionEnabled(TestAction::make('enabled')->schemaComponent('form.textInput'));

    livewire(Actions::class)
        ->assertFormComponentActionEnabled('textInput', 'enabled')
        ->assertFormComponentActionDisabled('textInput', 'disabled');
});

it('can have an icon', function () {
    livewire(Actions::class)
        ->assertActionHasIcon(TestAction::make('hasIcon')->schemaComponent('form.textInput'), 'heroicon-m-pencil-square')
        ->assertActionDoesNotHaveIcon(TestAction::make('hasIcon')->schemaComponent('form.textInput'), 'heroicon-m-trash');

    livewire(Actions::class)
        ->assertFormComponentActionHasIcon('textInput', 'hasIcon', 'heroicon-m-pencil-square')
        ->assertFormComponentActionDoesNotHaveIcon('textInput', 'hasIcon', 'heroicon-m-trash');
});

it('can have a label', function () {
    livewire(Actions::class)
        ->assertActionHasLabel(TestAction::make('hasLabel')->schemaComponent('form.textInput'), 'My Action')
        ->assertActionDoesNotHaveLabel(TestAction::make('hasLabel')->schemaComponent('form.textInput'), 'My Other Action');

    livewire(Actions::class)
        ->assertFormComponentActionHasLabel('textInput', 'hasLabel', 'My Action')
        ->assertFormComponentActionDoesNotHaveLabel('textInput', 'hasLabel', 'My Other Action');
});

it('can have a color', function () {
    livewire(Actions::class)
        ->assertActionHasColor(TestAction::make('hasColor')->schemaComponent('form.textInput'), 'primary')
        ->assertActionDoesNotHaveColor(TestAction::make('hasColor')->schemaComponent('form.textInput'), 'gray');

    livewire(Actions::class)
        ->assertFormComponentActionHasColor('textInput', 'hasColor', 'primary')
        ->assertFormComponentActionDoesNotHaveColor('textInput', 'hasColor', 'gray');
});

it('can have a URL', function () {
    livewire(Actions::class)
        ->assertActionHasUrl(TestAction::make('url')->schemaComponent('form.textInput'), 'https://filamentphp.com')
        ->assertActionDoesNotHaveUrl(TestAction::make('url')->schemaComponent('form.textInput'), 'https://google.com');

    livewire(Actions::class)
        ->assertFormComponentActionHasUrl('textInput', 'url', 'https://filamentphp.com')
        ->assertFormComponentActionDoesNotHaveUrl('textInput', 'url', 'https://google.com');
});

it('can open a URL in a new tab', function () {
    livewire(Actions::class)
        ->assertActionShouldOpenUrlInNewTab(TestAction::make('urlInNewTab')->schemaComponent('form.textInput'))
        ->assertActionShouldNotOpenUrlInNewTab(TestAction::make('urlNotInNewTab')->schemaComponent('form.textInput'));

    livewire(Actions::class)
        ->assertFormComponentActionShouldOpenUrlInNewTab('textInput', 'urlInNewTab')
        ->assertFormComponentActionShouldNotOpenUrlInNewTab('textInput', 'urlNotInNewTab');
});

it('can state whether a form component action exists', function () {
    livewire(Actions::class)
        ->assertActionExists(TestAction::make('exists')->schemaComponent('form.textInput'))
        ->assertActionDoesNotExist(TestAction::make('doesNotExist')->schemaComponent('form.textInput'));

    livewire(Actions::class)
        ->assertFormComponentActionExists('textInput', 'exists')
        ->assertFormComponentActionDoesNotExist('textInput', 'doesNotExist');
});
