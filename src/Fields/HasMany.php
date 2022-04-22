<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Fields;

use Laravel\Nova\Panel;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasMany extends \Laravel\Nova\Fields\HasMany
{

    /**
     * Make current field behaves as panel.
     *
     * @return HasMany
     */
    public function asPanel()
    {
        return $this;
    }
}
