![Nova Tabs, awesome resource tabs for Nova](banner.png)

[![Latest Version on Github](https://img.shields.io/packagist/v/eminiarts/nova-tabs.svg?style=flat)](https://packagist.org/packages/eminiarts/nova-tabs)

1. [Installation](#Installation)
2. [Usage](#Usage)
    1. [Tabs Panel](#tabs-panel)
    2. [Tabs Panel with Toolbar](#tabs-panel-with-toolbar)
    3. [Relationship Tabs](#relationship-tabs)
    4. [Combine Fields and Relations in Tabs](#combine-fields-and-relations-in-tabs)
    5. [Actions in Tabs](#actions-in-tabs)
    6. [Tabs on Edit View](#tabs-on-edit-view)
3. [Tab object](#tab-object)
4. [Customization](#customization)
    1. [Tab](#tab)
    2. [Default search](#default-search)
    3. [Display more than 5 items](#display-more-than-5-items)
5. [Upgrade to 1.0.0](#upgrade-to-1.0.0)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require eminiarts/nova-tabs
```

## Usage

### Tabs Panel

![image](https://user-images.githubusercontent.com/3426944/50060698-7835ec00-0197-11e9-8b9c-c7f1e67400db.png)

You can group fields of a resource into tabs, you can use an array or a Tab object (as of 1.4.0)::

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;

public function fields()
{
    return [

        new Tabs('Tabs', [
            'Balance'    => [
                Number::make('Balance', 'balance'),
                Number::make('Total', 'total'),
            ],
            'Other Info' => [
                Number::make('Paid To Date', 'paid_to_date'),
            ],
        ]),

    ];
}
```

or

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;

public function fields()
{
    return [
        Tabs::make('Tabs', [
            Tab::make('Balance', [
                Number::make('Balance', 'balance'),
                Number::make('Total', 'total'),
            ]),
            Tab::make('Other Info', [
                Number::make('Paid To Date', 'paid_to_date')
            ]),
        ]),

    ];
}
```

### Tabs with Toolbar

If you are only using Tabs without another default Panel, you can set `withToolbar` to `true`.

![image](https://user-images.githubusercontent.com/3426944/50448780-608efe00-0923-11e9-9d55-3dc3d8d896e1.png)


```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;

public function fields(Request $request)
{
    return [
        Tabs::make('Contact Details', [
            Tab::make('Address', [
                ID::make('Id', 'id')->rules('required'),
                Text::make('Email', 'email')->sortable(),
                Text::make('Phone', 'phone')->sortable(),
            ]),
            Tab::make('Relations', [
                BelongsTo::make('User'),
                MorphTo::make('Contactable')->types([
                    Client::class,
                    Invoice::class,
                ]),
            ]),
        ])->withToolbar(),
    ];
}
```

### Relationship Tabs

![image](https://user-images.githubusercontent.com/3426944/50060715-a3b8d680-0197-11e9-8f98-1cac8cf3fd83.png)

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;

class User extends Resource
{
    public function fields(Request $request)
    {
        return [
           Tabs::make('Relations', [
                HasMany::make('Invoices'),
                HasMany::make('Notes'),
                HasMany::make('Contacts')
            ]),

        ];
    }
}
```

### Combine Fields and Relations in Tabs

![image](https://user-images.githubusercontent.com/3426944/51089909-b3b2de80-1774-11e9-9100-d323accda7db.png)

![image](https://user-images.githubusercontent.com/3426944/51089905-aa297680-1774-11e9-9611-4446ca13ab4a.png)

```php
use Eminiarts\Tabs\Tabs;

public function fields(Request $request)
{
    return [
        Tabs::make(__('Client Custom Details'), [
            new Panel(__('Details'), [
                    ID::make('Id', 'id')->rules('required')->hideFromIndex(),
                    Text::make('Name', 'name'),
            ]),
            HasMany::make('Invoices')
        ]),
    ];
}
```

### Actions in Tabs

If your Model uses the `Laravel\Nova\Actions\Actionable` Trait you can put the Actions into a Tab like this:

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\ActionsInTabs; // Add this Trait
use Laravel\Nova\Actions\ActionResource; // Import the Resource

class Client extends Resource
{
    use ActionsInTabs; // Use this Trait

    public function fields(Request $request)
    {
        return [
            Tabs::make('Client Custom Details', [
                Tab::make('Address', [
                    ID::make('Id', 'id'),
                    Text::make('Name', 'name')->hideFromDetail(),
                ]),
                Tab::make('Invoices', [
                    HasMany::make('Invoices'),
                ]),
                Tab::make('Actions', [
                    $this->actionfield(), // Add Actions whererver you like.
                ]),
            ])->withToolbar(),
        ];
    }
}
```

### Tabs on Edit View

![image](https://user-images.githubusercontent.com/3426944/51790797-055a6080-219a-11e9-8da4-33a621093265.png)


If you want to show Tabs on the Edit View, use the `TabsOnEdit` Trait in your Resource.

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit; // Add this Trait

class Client extends Resource
{
    use TabsOnEdit; // Use this Trait
    //...
}
```

## Tab object

As of v1.4.0 it's possible to use a `Tab` class instead of an array to represent your tabs.

| Property    | Type                | Default     | Description                                                                                                                                                            |
|-------------|---------------------|-------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| name        | `string`            | `null`      | The name of the tab, used for the slug.  Defaults to the title if not set                                                                                              |
| showIf      | `bool` or `Closure` | `null`      | If the result is truthy the tab will be shown.  `showIf` takes priority over `showUnless` and if neither are set, `true` is assumed.                                   |
| showUnless  | `bool` or `Closure` | `null`      | If the result is falsy the tab will be shown.  `showIf` takes priority over `showUnless` and if neither are set, `true` is assumed.                                    |
| titleAsHtml | `bool`              | `false`     | Whether the given title should be rendered as HTML.  **This potentially leaves you vulnerable for an XSS attack. Take precaution using this.**                         |
| beforeIcon  | `string`            | `null`      | An icon (or anything else really) you want to render in front of the title.  **This potentially leaves you vulnerable for an XSS attack. Take precaution using this.** |
| afterIcon   | `string`            | `null`      | An icon (or anything else really) you want to render behind the title.  **This potentially leaves you vulnerable for an XSS attack. Take precaution using this.**      |
| tabClass    | `string` or `array` | Empty array | A string or string array of classes to add to the tab.  This sets the `tabClass` property, if you want to append you can use `addTabClass` instead.                    |
| bodyClass   | `string` or `array` | Empty array | A string or string array of classes to add to the tab's body.  This sets the `bodyClass` property, if you want to append you can use `addBodyClass` instead.           |

## Customization

### Default search

By default, the Tabs component moves the search input and the create button to the tabs. If you have a lot of tabs, you can move them back down to its own line:

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;

class User extends Resource
{

    public function fields(Request $request)
    {
        return [
            Tabs::make('Relations', [
                HasMany::make('Invoices')
            ])->defaultSearch(true),
        ];
    }
}
```

Set `->defaultSearch(true)` to revert it to its default.

![image](https://user-images.githubusercontent.com/3426944/50060732-dbc01980-0197-11e9-8f0c-6014132539a2.png)

### Display more than 5 items

By default, any `HasMany`, `BelongsToMany` and `MorphMany` fields show 5 items in their index. You can use Nova's built-in static property `$perPageViaRelationship` on the respective resource to show more (or less).
 
## Upgrade to 1.0.0
Thanks to [dkulyk/nova-tabs](https://github.com/dkulyk/nova-tabs) the Package got a lot simpler. 

- No need to use a Trait anymore. Remove all `AvailableTabFields` Traits in your Resources.
- Everything is in `Tabs` now. There is no `TabsPanel` anymore. Remove all `TabsPanels` and adjust your Fields according to this Readme.
