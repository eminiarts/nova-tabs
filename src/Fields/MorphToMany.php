<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Fields;

use Laravel\Nova\Panel;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class MorphToMany extends \Laravel\Nova\Fields\MorphToMany
{

    /**
     * Make current field behaves as panel.
     *
     * @return MorphToMany
     */
    public function asPanel()
    {
        return $this;
    }
}
