<?php

declare(strict_types=1);

namespace Eminiarts\Tabs;

use Eminiarts\Tabs\Contracts\TabContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Makeable;

/**
 * Class Tab
 * @package Eminiarts\Tabs
 *
 * @method static Tab make($title, array $fields)
 */
class Tab implements TabContract, \JsonSerializable, Arrayable
{
    use Makeable;

    /** @var string|\Closure */
    private $title;

    /** @var Field[] */
    private $fields;

    /** @var string|null */
    protected $name;

    /** @var bool|\Closure|null */
    protected $showIf;

    /** @var bool|\Closure|null */
    protected $showUnless;

    /** @var bool */
    protected $titleAsHtml = false;

    /** @var string|null */
    protected $beforeIcon;

    /** @var string|null */
    protected $afterIcon;

    /** @var string[] */
    protected $tabClass = [];

    /** @var string[] */
    protected $bodyClass = [];

    public function __construct($title, array $fields)
    {
        $this->title = $title;
        $this->fields = $fields;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function showIf($condition): self
    {
        if (\is_bool($condition) || \is_callable($condition)) {
            $this->showIf = $condition;

            return $this;
        }

        throw new \InvalidArgumentException('The $condition parameter must be a boolean or a closure returning one');
    }

    public function showUnless($condition): self
    {
        if (\is_bool($condition) || \is_callable($condition)) {
            $this->showUnless = $condition;

            return $this;
        }

        throw new \InvalidArgumentException('The $condition parameter must be a boolean or a closure returning one');
    }

    public function titleAsHtml(bool $titleAsHtml): self
    {
        $this->titleAsHtml = $titleAsHtml;

        return $this;
    }

    public function beforeIcon(string $iconAsHtml): self
    {
        $this->beforeIcon = $iconAsHtml;

        return $this;
    }

    public function afterIcon(string $iconAsHtml): self
    {
        $this->afterIcon = $iconAsHtml;

        return $this;
    }

    public function tabClass($classes): self
    {
        $this->tabClass = Arr::wrap($classes);

        return $this;
    }

    public function addTabClass($classes): self
    {
        $this->tabClass = array_merge($this->tabClass, Arr::wrap($classes));

        return $this;
    }

    public function bodyClass($classes): self
    {
        $this->bodyClass = Arr::wrap($classes);

        return $this;
    }

    public function addBodyClass($classes): self
    {
        $this->bodyClass = array_merge($this->bodyClass, Arr::wrap($classes));

        return $this;
    }

    public function toArray(): array
    {
        $title = $this->resolve($this->title);

        return [
            'title' => $title,
            'fields' => $this->fields,
            'name' => $this->name ?? $title,
            'slug' => Str::slug($this->name ?? $title),
            'shouldShow' => $this->shouldShow(),
            'titleAsHtml' => $this->titleAsHtml,
            'beforeIcon' => $this->beforeIcon,
            'afterIcon' => $this->afterIcon,
            'tabClass' => $this->tabClass,
            'bodyClass' => $this->bodyClass,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private function resolve($value)
    {
        if ($value instanceof \Closure) {
            return $value();
        }

        return $value;
    }

    private function shouldShow(): bool
    {
        if ($this->showIf !== null) {
            return $this->resolve($this->showIf);
        }

        if ($this->showUnless !== null) {
            return !$this->resolve($this->showUnless);
        }

        return true;
    }
}
