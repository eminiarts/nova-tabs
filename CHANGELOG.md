# Changelog

## 2.0.7 - 2022-05-16
- Fixed that primary color is used if none is specified

## 2.0.6 - 2022-05-13
- Added functionality to specify the colors used for the current tab using Tabs::make()->withCurrentColor('sky') (default is sky)
- Added functionality to specify the colors used for tabs with errors using Tabs::make()->withErrorColor('red') (default is red)
- Color options for both are all the 500 variants based on the default tailwind colors, reference: https://tailwindcss.com/docs/customizing-colors

## 2.0.5 - 2022-05-13
- Fixed that errors on form submissions are now reflected on tabs

## 2.0.4 - 2022-05-13
- Fixed padding issues
- Fixed codemirror fields and styles
- Added withSlug function to Tabs panel
  - withSlug takes a boolean/string parameter. If true will slugify the panel name, if a string it will use that string as the panel slug
  - HasTabs mixin checks for non-null slug and uses it instead of name
  - adds preservedName variable to retain the name after the parent panel is created
  - HasTabs Trait gets slug on panel recreation
- Fixed naming issue for mix.js conflicting with "mix" command in some environments
- Added the ability to retain the current tab when swapping between detail and edit views using Tabs::make()->rememberTabs()

## 2.0.3 - 2022-05-01
- Fixed padding and removed console.log

## 2.0.2 - 2022-05-01
- Fixed a bug where padding was wrong when not showing toolbar

## 2.0.1 - 2022-05-01
- Fixed a bug where clicking a tab would scroll the page to top
- Fixed a bug with tab persistence supplying the wrong location has
- Fixed a bug where relationship panels that aren't under tabs would show up wrong
- Fixed a bug where showToolbar isn't utilized properly

## 2.0.0 - 2022-04-30
- Nova 4 compatible
- Removed selectFirstTab, first tab is always displayed first.
- Removed TabsOnEdit, tabs will simply always show on edit for now.
- Added Eminiarts\Tabs\Traits\HasTabs to overwrite Nova 4 default panel methods in Laravel\Nova\ResolveFields.
- Added position method to Tab to fix relational tabs showing up last.
- Moved Eminiarts\Tabs\ActionsInTabs to Eminiaarts\Tabs\Traits\HasActionsInTabs.

## 1.5.1 - 2022-02-24

- Fixed issue with tabs not showing up. ([#172](https://github.com/eminiarts/nova-tabs/issues/172))

## 1.5.0 - 2022-02-13

- Added `selectFirstTab(false)` and `dontSelectFirstTab()` ([#165](https://github.com/eminiarts/nova-tabs/pull/165))
  - This method can be used to opt out from the default selected tab. This can be useful when your relations are particularly heavy
- Fixed issue with third-party fields implementing `MergeValue` causing the tab to lose its slug value ([#162](https://github.com/eminiarts/nova-tabs/pull/162))
- Fixed issue with files not being able to be deleted within tabs ([#169](https://github.com/eminiarts/nova-tabs/pull/169))
- Fixed h1 selector in `DetailTabs` breaking certain packages like Quill and Editor.js ([#171](https://github.com/eminiarts/nova-tabs/pull/171))

## 1.4.6 - 2021-07-12

- Revert production build files that broke the package on detail views. ([#156](https://github.com/eminiarts/nova-tabs/issues/156))

## 1.4.5 - 2021-07-11

- Fixed the inability to use Laravel Dusk to run tests. ([#155](https://github.com/eminiarts/nova-tabs/pull/155))

## 1.4.4 - 2021-07-10

- Fixed an issue using `TabsOnEdit` causing rules relying on the current resources to not work properly. ([#154](https://github.com/eminiarts/nova-tabs/pull/154))

## 1.4.3 - 2021-03-07

- Fixed issue with `Laravel\Nova\Makeable` not existing. ([#144](https://github.com/eminiarts/nova-tabs/issues/144))

## 1.4.2 - 2021-02-28

- Fixed issue with 2nd tabs not showing if a tab has been selected.

## 1.4.1 - 2021-02-03

- Fixed crash caused by fields extending `MergeValue` not having a `$panel` property. ([#140](https://github.com/eminiarts/nova-tabs/issues/140))
- Fixed crash by fields not being nested into an array or a `Tab` object. ([#141](https://github.com/eminiarts/nova-tabs/issues/141))

## 1.4.0 - 2021-02-03

- Added `Tab` class. ([#129](https://github.com/eminiarts/nova-tabs/issues/129), [#130](https://github.com/eminiarts/nova-tabs/issues/130), [#138](https://github.com/eminiarts/nova-tabs/pull/138))
  - This class can be used as an alternative to using arrays and offers way more features. Check the README for info and documentation.
- Fixed margin on the search bar when defaultSearch is enabled. ([#134](https://github.com/eminiarts/nova-tabs/pull/134))
- `ActionsInTabs` now allows use of the `Laravel\Nova\ResolvesFields::actionField` method. ([#135](https://github.com/eminiarts/nova-tabs/pull/135))
- Added a pretty banner, courtesy of https://banners.beyondco.de/
- Fix panel order on edit page. ([#85](https://github.com/eminiarts/nova-tabs/issues/85), [#139](https://github.com/eminiarts/nova-tabs/pull/139))
- Fixed an issue causing field that did not have a panel set to not slow correctly or at all. ([#112](https://github.com/eminiarts/nova-tabs/issues/112))

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
