# Changelog

## 1.3.1 - 2020-11-22

- Fixed issue with files not being deletable when using `TabsOnEdit` ([#28](https://github.com/eminiarts/nova-tabs/issues/28) and [#59](https://github.com/eminiarts/nova-tabs/pulls/59))
- Fixed issue with switching tabs causing the query string to be overwritten completely ([#124](https://github.com/eminiarts/nova-tabs/issues/124))
- Fixed a styling issue causing the search bar to not be in line with the create/attach button ([#121](https://github.com/eminiarts/nova-tabs/issues/121))

## 1.3.0 - 2020-11-05

- Active tabs are now remembered ([#86](https://github.com/eminiarts/nova-tabs/pull/86) and [#119](https://github.com/eminiarts/nova-tabs/pull/119))
- Titles of tabs can now be shown by chaining `->showTitle()` on the `Tabs` instance. Off by default. ([#118](https://github.com/eminiarts/nova-tabs/issue/118))
- Fixed an overflow issue when using `TabsOnEdit` ([#114](https://github.com/eminiarts/nova-tabs/pull/114))
- Fixed bottom margin on create and attach buttons not being applied ([#55](https://github.com/eminiarts/nova-tabs/pull/55))
- Fixed issue with code fields not appearing on load ([#23](https://github.com/eminiarts/nova-tabs/issue/23))

## 1.2.3 - 2020-10-11

- Fixed help text not showing up on fields (#108)
- Fixed readonly fields being ignored during a fill (#91)
- Fixed `TabsOnEdit` not showing anything in Nova 3.12.x (#111)

## 1.0.3 - 2019-02-03

- Fixed #15 overlapping Tabs
- ViaRelations on Fields Components, Fixed #18

## 1.0.2 - 2019-02-03

- Add Tabs on Create

## 1.0.1 - 2019-02-03

- Added HandlesValidationErrors in FormsTabs Component.

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
