<?php

namespace Filament\Tests\Actions\Fixtures\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class Actions extends Page
{
    protected static string $view = 'actions.fixtures.pages.actions';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('simple')
                ->action(function () {
                    $this->dispatch('simple-called');
                }),
            Action::make('data')
                ->mountUsing(fn (Schema $form) => $form->fill(['foo' => 'bar']))
                ->form([
                    TextInput::make('payload')->required(),
                ])
                ->action(function (array $data) {
                    $this->dispatch('data-called', data: $data);
                }),
            Action::make('before-hook-data')
                ->form([
                    TextInput::make('payload')->required(),
                ])
                ->before(function (Action $action) {
                    $this->dispatch('before-hook-called', data: $action->getFormData());
                }),
            Action::make('arguments')
                ->requiresConfirmation()
                ->action(function (array $arguments) {
                    $this->dispatch('arguments-called', arguments: $arguments);
                })
                ->extraModalFooterActions(fn (): array => [
                    Action::make('nested')
                        ->requiresConfirmation()
                        ->action(function (array $arguments) {
                            $this->dispatch('nested-called', arguments: $arguments);
                        }),
                ]),
            Action::make('parent')
                ->schema([
                    TextInput::make('foo')
                        ->required()
                        ->registerActions([
                            Action::make('nested')
                                ->schema([
                                    TextInput::make('bar')
                                        ->required(),
                                ])
                                ->action(fn () => null),
                            Action::make('cancelParent')
                                ->schema([
                                    TextInput::make('bar')
                                        ->required(),
                                ])
                                ->action(fn () => null)
                                ->cancelParentActions(),
                        ]),
                ])
                ->action(function (array $data) {
                    $this->dispatch('parent-called', foo: $data['foo']);
                })
                ->extraModalFooterActions([
                    Action::make('footer')
                        ->schema([
                            TextInput::make('bar')
                                ->required(),
                        ])
                        ->action(fn () => null),
                ])
                ->registerModalActions([
                    Action::make('manuallyRegisteredModal')
                        ->schema([
                            TextInput::make('bar')
                                ->required(),
                        ])
                        ->registerModalActions([
                            Action::make('testData')
                                ->schema([
                                    TextInput::make('baz')
                                        ->required(),
                                ])
                                ->action(fn (array $mountedActions) => $this->dispatch(
                                    'data-test-called',
                                    foo: $mountedActions[0]->getRawFormData()['foo'],
                                    bar: $mountedActions[1]->getRawFormData()['bar'],
                                    baz: $mountedActions[2]->getRawFormData()['baz'],
                                )),
                            Action::make('testArguments')
                                ->action(function (array $mountedActions, Action $action) {
                                    $this->dispatch(
                                        'arguments-test-called',
                                        foo: $mountedActions[0]->getArguments()['foo'],
                                        bar: $mountedActions[1]->getArguments()['bar'],
                                        baz: $mountedActions[2]->getArguments()['baz'],
                                    );
                                }),
                        ])
                        ->action(fn () => null),
                ]),
            Action::make('halt')
                ->requiresConfirmation()
                ->action(function (Action $action) {
                    $this->dispatch('halt-called');

                    $action->halt();
                }),
            Action::make('visible'),
            Action::make('hidden')
                ->hidden(),
            Action::make('enabled'),
            Action::make('disabled')
                ->disabled(),
            Action::make('hasIcon')
                ->icon('heroicon-m-pencil-square'),
            Action::make('hasLabel')
                ->label('My Action'),
            Action::make('hasColor')
                ->color('primary'),
            Action::make('exists'),
            Action::make('url')
                ->url('https://filamentphp.com'),
            Action::make('urlInNewTab')
                ->url('https://filamentphp.com', true),
            Action::make('urlNotInNewTab')
                ->url('https://filamentphp.com', false),
            Action::make('shows-notification')
                ->action(function () {
                    Notification::make()
                        ->title('A notification')
                        ->success()
                        ->send();
                }),
            Action::make('does-not-show-notification'),
            Action::make('shows-notification-with-id')
                ->action(function () {
                    Notification::make('notification_with_id')
                        ->title('A notification')
                        ->success()
                        ->send();
                }),
            Action::make('two-notifications')
                ->action(function () {
                    Notification::make('first_notification')
                        ->title('First notification')
                        ->success()
                        ->send();
                    Notification::make('second_notification')
                        ->title('Second notification')
                        ->success()
                        ->send();
                }),
        ];
    }
}
