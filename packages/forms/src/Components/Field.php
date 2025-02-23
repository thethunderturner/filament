<?php

namespace Filament\Forms\Components;

use Closure;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use Filament\Schemas\Components\StateCasts\EnumStateCast;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;

class Field extends Component implements Contracts\HasValidationRules
{
    use Concerns\CanBeAutofocused;
    use Concerns\CanBeMarkedAsRequired;
    use Concerns\CanBeValidated;
    use Concerns\CanDisableGrammarly;
    use Concerns\HasEnum;
    use Concerns\HasExtraFieldWrapperAttributes;
    use Concerns\HasHelperText;
    use Concerns\HasHint;
    use Concerns\HasName;

    protected string $viewIdentifier = 'field';

    const ABOVE_LABEL_SCHEMA_KEY = 'above_label';

    const BELOW_LABEL_SCHEMA_KEY = 'below_label';

    const BEFORE_LABEL_SCHEMA_KEY = 'before_label';

    const AFTER_LABEL_SCHEMA_KEY = 'after_label';

    const ABOVE_CONTENT_SCHEMA_KEY = 'above_content';

    const BELOW_CONTENT_SCHEMA_KEY = 'below_content';

    const BEFORE_CONTENT_SCHEMA_KEY = 'before_content';

    const AFTER_CONTENT_SCHEMA_KEY = 'after_content';

    const ABOVE_ERROR_MESSAGE_SCHEMA_KEY = 'above_error_message';

    const BELOW_ERROR_MESSAGE_SCHEMA_KEY = 'below_error_message';

    final public function __construct(string $name)
    {
        $this->name($name);
        $this->statePath($name);
    }

    public static function make(?string $name = null): static
    {
        $fieldClass = static::class;

        $name ??= static::getDefaultName();

        if ($name === null) {
            throw new Exception("Field of class [$fieldClass] must have a unique name, passed to the [make()] method.");
        }

        $static = app($fieldClass, ['name' => $name]);

        $static->configure();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpHint();
    }

    public static function getDefaultName(): ?string
    {
        return null;
    }

    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        $casts = parent::getDefaultStateCasts();

        if ($enumStateCast = $this->getEnumDefaultStateCast()) {
            $casts[] = $enumStateCast;
        }

        return $casts;
    }

    public function getEnumDefaultStateCast(): ?StateCast
    {
        $enum = $this->getEnum();

        if (blank($enum)) {
            return null;
        }

        return app(
            EnumStateCast::class,
            ['enum' => $enum],
        );
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function aboveLabel(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::ABOVE_LABEL_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function belowLabel(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::BELOW_LABEL_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function beforeLabel(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::BEFORE_LABEL_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function afterLabel(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::AFTER_LABEL_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function aboveContent(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::ABOVE_CONTENT_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function belowContent(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::BELOW_CONTENT_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function beforeContent(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::BEFORE_CONTENT_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function afterContent(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::AFTER_CONTENT_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function aboveErrorMessage(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::ABOVE_ERROR_MESSAGE_SCHEMA_KEY);

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup | string> | Schema | Component | Action | ActionGroup | string | Closure | null  $components
     */
    public function belowErrorMessage(array | Schema | Component | Action | ActionGroup | string | Closure | null $components): static
    {
        $this->childComponents($components, static::BELOW_ERROR_MESSAGE_SCHEMA_KEY);

        return $this;
    }

    protected function makeChildSchema(string $key): Schema
    {
        $schema = parent::makeChildSchema($key);

        if (in_array($key, [static::AFTER_LABEL_SCHEMA_KEY, static::AFTER_CONTENT_SCHEMA_KEY])) {
            $schema->alignEnd();
        }

        return $schema;
    }

    protected function configureChildSchema(Schema $schema, string $key): Schema
    {
        $schema = parent::configureChildSchema($schema, $key);

        if (in_array($key, [
            static::ABOVE_LABEL_SCHEMA_KEY,
            static::BELOW_LABEL_SCHEMA_KEY,
            static::BEFORE_LABEL_SCHEMA_KEY,
            static::AFTER_LABEL_SCHEMA_KEY,
            static::ABOVE_CONTENT_SCHEMA_KEY,
            static::BELOW_CONTENT_SCHEMA_KEY,
            static::BEFORE_CONTENT_SCHEMA_KEY,
            static::AFTER_CONTENT_SCHEMA_KEY,
            static::ABOVE_ERROR_MESSAGE_SCHEMA_KEY,
            static::BELOW_ERROR_MESSAGE_SCHEMA_KEY,
        ])) {
            $schema
                ->inline()
                ->embeddedInParentComponent()
                ->configureActionsUsing(fn (Action $action) => $action
                    ->defaultSize(Size::Small)
                    ->defaultView(Action::LINK_VIEW))
                ->configureActionGroupsUsing(fn (ActionGroup $actionGroup) => $actionGroup->defaultSize(Size::Small));
        }

        return $schema;
    }
}
