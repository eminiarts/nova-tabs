# Laravel Nova Tabs Package

1. [Installation](#Installation)
2. [Usage](#Usage)
    1. [Tabs Panel](#tabs-panel)
    2. [Tabs Panel with Toolbar](#tabs-panel-with-toolbar)
    3. [Relationship Tabs](#relationship-tabs)
    4. [Combine Fields and Relations in Tabs](#combine-fields-and-relations-in-tabs)
3. [Customization](#customization)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require eminiarts/nova-tabs
```

## Usage

### Tabs Panel

![image](https://user-images.githubusercontent.com/3426944/50060698-7835ec00-0197-11e9-8b9c-c7f1e67400db.png)

You can group Fields of a Resource into Tabs with a TabsPanel.

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\TabsPanel;

public function fields()
{
    return [
        
        // ...
        
        new TabsPanel('Tabs', [
            'Balance'    => [
                Number::make('Balance', 'balance')->onlyOnDetail(),
                Number::make('Total', 'total')->onlyOnDetail(),
            ],
            'Other Info' => [
                Number::make('Paid To Date', 'paid_to_date')->onlyOnDetail(),
            ],
        ]),
        
        // ...
        
    ];
}
```

### Tabs Panel with Toolbar

If you are only using a TabsPanel without another default Panel, you can set the third argument `showToolbar` to `true`.

![image](https://user-images.githubusercontent.com/3426944/50448780-608efe00-0923-11e9-9d55-3dc3d8d896e1.png)


```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\TabsPanel;

public function fields(Request $request)
    {
        return [
            new TabsPanel('Contact Details', [
                'Address'   => [
                    ID::make('Id', 'id')->rules('required'),
                    Text::make('Email', 'email')->sortable(),
                    Text::make('Phone', 'phone')->sortable(),
                ],
                'Relations' => [
                    BelongsTo::make('User'),
                    MorphTo::make('Contactable')->types([
                        Client::class,
                        Invoice::class,
                    ]),
                ],
            ], true), // Third argument is $showToolbar = true
        ];
    }
```

### Relationship Tabs

![image](https://user-images.githubusercontent.com/3426944/50060715-a3b8d680-0197-11e9-8f98-1cac8cf3fd83.png)

You can also group Relations into Tabs. Make sure to use the `AvailableTabFields` Trait in your Nova Resource.

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\AvailableTabFields;

class User extends Resource
{
        
    use AvailableTabFields;

    public function fields(Request $request)
    {
        return [
            
            // ...
            
            Tabs::make('Relations')
                ->addTab(__('Invoices'), HasMany::make('Invoices'))
                ->addTab(__('Notes'), HasMany::make('Notes'))
                ->addTab(__('Contacts'), HasMany::make('Contacts'))
            ,

            // ...
            
        ];
    }

}
```

### Combine Fields and Relations in Tabs

![image](https://user-images.githubusercontent.com/3426944/51089909-b3b2de80-1774-11e9-9100-d323accda7db.png)

![image](https://user-images.githubusercontent.com/3426944/51089905-aa297680-1774-11e9-9611-4446ca13ab4a.png)

With this markup, it is possible to wrap tabs and show the toolbar:

```php
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsPanel;

public function fields(Request $request)
{
    return [
        (new TabsPanel(__('Client Custom Details'), [
            Tabs::make()
                ->addTab(__('Details'), [
                    ID::make('Id', 'id')->rules('required')->hideFromIndex(),
                    Text::make('Name', 'name'),
                ])
                ->addTab(__('Invoices'), HasMany::make('Invoices'))
            ,
        ]))->withToolbar(),
    ];
}
```

If you don't need the Toolbar and want to combine Fields with Relation, you can use:

```php
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsPanel;

public function fields(Request $request)
{
    return [
       
           Tabs::make()
                ->addTab(__('Details'), [
                    ID::make('Id', 'id')->rules('required')->hideFromIndex(),
                    Text::make('Name', 'name'),
                ])
                ->addTab(__('Invoices'), HasMany::make('Invoices'))
            ,
       
    ];
}
```

## Customization

By default, the Tabs component moves the search input and the create button to the tabs. If you have a lot of tabs, you can move them back down to its own line:

```php
// in app/Nova/Resource.php

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\AvailableTabFields;

class User extends Resource
{
        
    use AvailableTabFields;

    public function fields(Request $request)
    {
        return [
            
            // ...
            
            Tabs::make('Relations')
                ->addTab(__('Invoices'), HasMany::make('Invoices'))
                ->defaultSearch(true),
            ,

            // ...
            
        ];
    }
}
```

Set `->defaultSearch(true)` to revert it to its default.

![image](https://user-images.githubusercontent.com/3426944/50060732-dbc01980-0197-11e9-8f0c-6014132539a2.png)



