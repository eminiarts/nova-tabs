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
    protected $title;

    /** @var Field[] */
    protected $fields;

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

    /**
     * @return \Closure|string
     */
    public function getTitle(): string
    {
        return (string) $this->resolve($this->title);
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name ?? $this->getTitle();
    }

    public function getSlug(): string
    {
        return Str::slug($this->getName());
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

    public function shouldShow(): bool
    {
        if ($this->showIf !== null) {
            return $this->resolve($this->showIf);
        }

        if ($this->showUnless !== null) {
            return !$this->resolve($this->showUnless);
        }

        return true;
    }

    public function titleAsHtml(bool $titleAsHtml = true): self
    {
        $this->titleAsHtml = $titleAsHtml;

        return $this;
    }

    public function isTitleAsHtml(): bool
    {
        return $this->titleAsHtml;
    }

    public function beforeIcon(string $iconAsHtml): self
    {
        $this->beforeIcon = $iconAsHtml;

        return $this;
    }

    public function getBeforeIcon(): ?string
    {
        return $this->beforeIcon;
    }

    public function afterIcon(string $iconAsHtml): self
    {
        $this->afterIcon = $iconAsHtml;

        return $this;
    }

    public function getAfterIcon(): ?string
    {
        return $this->afterIcon;
    }

    public function tabClass($classes): self
    {
        $this->tabClass = Arr::wrap($classes);

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTabClass(): array
    {
        return $this->tabClass;
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

    /**
     * @return string[]
     */
    public function getBodyClass(): array
    {
        return $this->bodyClass;
    }

    public function addBodyClass($classes): self
    {
        $this->bodyClass = array_merge($this->bodyClass, Arr::wrap($classes));

        return $this;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'fields' => $this->getFields(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'shouldShow' => $this->shouldShow(),
            'titleAsHtml' => $this->isTitleAsHtml(),
            'beforeIcon' => $this->getBeforeIcon(),
            'afterIcon' => $this->getAfterIcon(),
            'tabClass' => $this->getTabClass(),
            'bodyClass' => $this->getBodyClass(),
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
}
