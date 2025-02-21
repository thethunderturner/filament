<?php

namespace Filament\Resources\Pages\Concerns;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\NestedSchema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;

trait HasWizard /** @phpstan-ignore trait.unused */
{
    public function getStartStep(): int
    {
        return 1;
    }

    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->alpineSubmitHandler("\$wire.{$this->getSubmitFormLivewireMethodName()}()")
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    public function hasFormWrapper(): bool
    {
        return false;
    }

    /**
     * @return array<Component | Action | ActionGroup>
     */
    public function getFormContentComponents(): array
    {
        return [
            NestedSchema::make('form'),
        ];
    }

    public function getSteps(): array
    {
        return [];
    }

    protected function hasSkippableSteps(): bool
    {
        return false;
    }
}
