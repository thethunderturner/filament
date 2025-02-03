<?php

namespace Filament\Forms;

use Filament\Forms\Testing\TestsFormComponentActions;
use Filament\Forms\Testing\TestsForms;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FormsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-forms')
            ->hasCommands([
                Commands\MakeFieldCommand::class,
                Commands\MakeFormCommand::class,
                Commands\MakeLivewireFormCommand::class,
            ])
            ->hasTranslations()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('color-picker', __DIR__ . '/../dist/components/color-picker.js'),
            AlpineComponent::make('date-time-picker', __DIR__ . '/../dist/components/date-time-picker.js'),
            AlpineComponent::make('file-upload', __DIR__ . '/../dist/components/file-upload.js'),
            AlpineComponent::make('key-value', __DIR__ . '/../dist/components/key-value.js'),
            AlpineComponent::make('markdown-editor', __DIR__ . '/../dist/components/markdown-editor.js'),
            AlpineComponent::make('rich-editor', __DIR__ . '/../dist/components/rich-editor.js'),
            AlpineComponent::make('select', __DIR__ . '/../dist/components/select.js'),
            AlpineComponent::make('slider', __DIR__ . '/../dist/components/slider.js'),
            AlpineComponent::make('tags-input', __DIR__ . '/../dist/components/tags-input.js'),
            AlpineComponent::make('textarea', __DIR__ . '/../dist/components/textarea.js'),
            Css::make('forms', __DIR__ . '/../dist/index.css'),
        ], 'filament/forms');

        if ($this->app->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament/{$file->getFilename()}"),
                ], 'filament-stubs');
            }
        }

        Testable::mixin(new TestsForms);
        Testable::mixin(new TestsFormComponentActions);
    }
}
