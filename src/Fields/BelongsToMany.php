<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Fields;

use Laravel\Nova\Panel;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class BelongsToMany extends \Laravel\Nova\Fields\BelongsToMany
{

    /**
     * Make current field behaves as panel.
     *
     * @return BelongsToMany
     */
    public function asPanel()
    {
        return $this;
    }
}
