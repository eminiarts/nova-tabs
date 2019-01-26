# Changelog

## 1.0.0 - 2019-01-26

- Thanks to [dkulyk/nova-tabs](https://github.com/dkulyk/nova-tabs) the Package got a lot simpler. 
- No need to use a Trait anymore. Remove all `AvailableTabFields` Traits in your Resources.
- Everything is in `Tabs` now. There is no `TabsPanel` anymore. Remove all `TabsPanels` and adjust your Fields according to the Readme.

## 0.3.1 - 2019-01-14

- Fixed resolving Fields. `->hideFromDetail()` is working in Tabs now. #9
- Fixed undefined Groups. #8

## 0.3.0 - 2019-01-13

- Added ability to use Tabs with TabsPanel and combine Fields with Relations in Tabs.
- Fixed #7

## 0.2.0 - 2019-01-08

- Added `AvailableTabFields` Trait to resolve the correct Fields from Tabs when Nova needs the field.
