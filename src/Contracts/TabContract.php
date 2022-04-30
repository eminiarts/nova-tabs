<?php

declare(strict_types=1);

namespace Eminiarts\Tabs\Contracts;

use Closure;

interface TabContract
{
    /**
     * The name of the tab
     *
     * This will be used to remember which tab is selected
     * If the name if not supplied, the sluggified tab title is used
     *
     * @param  string  $name
     *
     * @return $this
     */
    public function name(string $name);

    /**
     * A boolean or function that returns a boolean
     *
     * If the result is true, the tab will be shown
     *
     * showIf takes priority over showUnless
     *
     * @param  bool | Closure  $condition
     *
     * @return $this
     */
    public function showIf($condition);

    /**
     * A boolean or function that returns a boolean
     *
     * If the result is false, the tab will be shown
     *
     * showIf takes priority over showUnless
     *
     * @param  bool | Closure  $condition
     *
     * @return $this
     */
    public function showUnless($condition);

//    /**
//     * Whether to show the title as HTML
//     *
//     * WARNING: setting this to true can leave you open to XSS attacks
//     * Only use this if the title comes from a trusted source
//     *
//     * @param bool $titleAsHtml
//     *
//     * @return $this
//     */
//    public function titleAsHtml(bool $titleAsHtml);
//
//    /**
//     * An icon to be put in front of the tab title
//     *
//     * @param string $iconAsHtml
//     *
//     * @return $this
//     */
//    public function beforeIcon(string $iconAsHtml);
//
//    /**
//     * An icon to be put behind of the tab title
//     *
//     * @param string $iconAsHtml
//     *
//     * @return $this
//     */
//    public function afterIcon(string $iconAsHtml);
//
//    /**
//     * A string or string array of classes to put on the tab
//     *
//     * @param string|string[] $classes
//     * @return $this
//     */
//    public function tabClass($classes);

    /**
     * A string or string array of classes to put on the body
     *
     * @param  string|string[]  $classes
     * @return $this
     */
    public function bodyClass($classes);

    /**
     * Array representation of the tab
     *
     * @return array
     */
    public function toArray(): array;
}
