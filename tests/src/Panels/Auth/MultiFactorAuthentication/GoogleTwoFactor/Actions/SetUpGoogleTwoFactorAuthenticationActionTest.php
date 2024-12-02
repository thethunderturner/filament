<?php

use Filament\Actions\Testing\Fixtures\TestAction;
use Filament\Facades\Filament;
use Filament\Pages\Auth\EditProfile;
use Filament\Tests\Models\User;
use Filament\Tests\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Filament\Tests\livewire;
use function Pest\Laravel\actingAs;

uses(TestCase::class);

beforeEach(function () {
    Filament::setCurrentPanel('google-two-factor-authentication');

    actingAs(User::factory()->create());
});

it('can generate a secret and recovery codes when the action is mounted', function () {
    livewire(EditProfile::class)
        ->mountAction(TestAction::make('setUpGoogleTwoFactorAuthentication')
            ->schemaComponent('form.google_two_factor.setUpGoogleTwoFactorAuthenticationAction'))
        ->assertActionMounted(TestAction::make('setUpGoogleTwoFactorAuthentication')
            ->schemaComponent('form.google_two_factor.setUpGoogleTwoFactorAuthenticationAction')
            ->arguments(function (array $actualArguments): bool {
                $encrypted = decrypt($actualArguments['encrypted']);

                if (blank($encrypted['secret'] ?? null)) {
                    return false;
                }

                if (blank($encrypted['recoveryCodes'] ?? null)) {
                    return false;
                }

                if (count($encrypted['recoveryCodes']) !== 8) {
                    return false;
                }

                foreach ($encrypted['recoveryCodes'] as $recoveryCode) {
                    if (! is_string($recoveryCode)) {
                        return false;
                    }

                    if (blank($recoveryCode)) {
                        return false;
                    }
                }

                if (blank($encrypted['userId'] ?? null)) {
                    return false;
                }

                return $encrypted['userId'] === auth()->id();
            }));
});

it('can save the secret and recovery codes to the user when the action is submitted', function () {
    $googleTwoFactorAuthentication = Arr::first(Filament::getCurrentPanel()->getMultiFactorAuthenticationProviders());

    $user = auth()->user();

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();

    $livewire = livewire(EditProfile::class)
        ->mountAction(TestAction::make('setUpGoogleTwoFactorAuthentication')
            ->schemaComponent('form.google_two_factor.setUpGoogleTwoFactorAuthenticationAction'));

    $encryptedActionArguments = decrypt($livewire->instance()->mountedActions[0]['arguments']['encrypted']);
    $secret = $encryptedActionArguments['secret'];
    $recoveryCodes = $encryptedActionArguments['recoveryCodes'];

    $livewire
        ->setActionData(['code' => $googleTwoFactorAuthentication->getCurrentCode($user, $secret)])
        ->callMountedAction()
        ->assertHasNoActionErrors();

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeTrue();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBe($secret);

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toHaveCount(8);

    foreach ($user->getGoogleTwoFactorAuthenticationRecoveryCodes() as $hashedRecoveryCode) {
        expect(Hash::check(array_shift($recoveryCodes), $hashedRecoveryCode))
            ->toBeTrue();
    }
});

it('will not set up authentication when an invalid code is used', function () {
    $googleTwoFactorAuthentication = Arr::first(Filament::getCurrentPanel()->getMultiFactorAuthenticationProviders());

    $user = auth()->user();

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();

    $livewire = livewire(EditProfile::class)
        ->mountAction(TestAction::make('setUpGoogleTwoFactorAuthentication')
            ->schemaComponent('form.google_two_factor.setUpGoogleTwoFactorAuthenticationAction'));

    $encryptedActionArguments = decrypt($livewire->instance()->mountedActions[0]['arguments']['encrypted']);
    $secret = $encryptedActionArguments['secret'];

    $livewire
        ->setActionData([
            'code' => ($googleTwoFactorAuthentication->getCurrentCode($user, $secret) === '000000') ? '111111' : '000000',
        ])
        ->callMountedAction()
        ->assertHasActionErrors();

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();
});

test('codes are required', function () {
    $user = auth()->user();

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();

    livewire(EditProfile::class)
        ->mountAction(TestAction::make('setUpGoogleTwoFactorAuthentication')
            ->schemaComponent('form.google_two_factor.setUpGoogleTwoFactorAuthenticationAction'))
        ->setActionData(['code' => ''])
        ->callMountedAction()
        ->assertHasActionErrors([
            'code' => 'required',
        ]);

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();
});

test('codes must be 6 digits', function () {
    $googleTwoFactorAuthentication = Arr::first(Filament::getCurrentPanel()->getMultiFactorAuthenticationProviders());

    $user = auth()->user();

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();

    $livewire = livewire(EditProfile::class)
        ->mountAction(TestAction::make('setUpGoogleTwoFactorAuthentication')
            ->schemaComponent('form.google_two_factor.setUpGoogleTwoFactorAuthenticationAction'));

    $encryptedActionArguments = decrypt($livewire->instance()->mountedActions[0]['arguments']['encrypted']);
    $secret = $encryptedActionArguments['secret'];

    $livewire
        ->setActionData([
            'code' => Str::limit($googleTwoFactorAuthentication->getCurrentCode($user, $secret), limit: 5, end: ''),
        ])
        ->callMountedAction()
        ->assertHasActionErrors([
            'code' => 'digits',
        ]);

    expect($user->hasGoogleTwoFactorAuthentication())
        ->toBeFalse();

    expect($user->getGoogleTwoFactorAuthenticationSecret())
        ->toBeEmpty();

    expect($user->getGoogleTwoFactorAuthenticationRecoveryCodes())
        ->toBeArray()
        ->toBeEmpty();
});
