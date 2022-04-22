<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Fields;

use Laravel\Nova\Panel;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasManyThrough extends \Laravel\Nova\Fields\HasManyThrough
{

    /**
     * Make current field behaves as panel.
     *
     * @return HasManyThrough
     */
    public function asPanel()
    {
        return $this;
    }
}
