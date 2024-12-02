<?php

namespace Filament\Widgets;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends Widget implements HasSchemas
{
    use Concerns\CanPoll;
    use InteractsWithSchemas;

    /**
     * @var array<Stat> | null
     */
    protected ?array $cachedStats = null;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = null;

    protected ?string $description = null;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-widgets::stats-overview-widget';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading($this->getHeading())
                    ->description($this->getDescription())
                    ->schema($this->getCachedStats())
                    ->columns($this->getColumns())
                    ->contained(false),
            ]);
    }

    /**
     * @return array<string, int | string | null> | int | string | null
     */
    protected function getColumns(): array | int | string | null
    {
        $count = count($this->getCachedStats());

        if ($count < 3) {
            return 3;
        }

        if (($count % 3) !== 1) {
            return 3;
        }

        return 4;
    }

    protected function getDescription(): ?string
    {
        return $this->description;
    }

    protected function getHeading(): ?string
    {
        return $this->heading;
    }

    /**
     * @return array<Stat>
     */
    protected function getCachedStats(): array
    {
        return $this->cachedStats ??= $this->getStats();
    }

    /**
     * @deprecated Use `getStats()` instead.
     *
     * @return array<Stat>
     */
    protected function getCards(): array
    {
        return [];
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        return $this->getCards();
    }
}
