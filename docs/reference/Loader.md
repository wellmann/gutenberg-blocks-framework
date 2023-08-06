---
layout: default
title: Loader
parent: Reference
has_toc: false
---

# Loader
{: .no_toc }

Class to initialize the framework.



* Full name: `\KWIO\GutenbergBlocks\Loader`


<details open markdown="block">
  <summary>
    Table of contents
  </summary>
  {: .text-delta }
1. TOC
{:toc}
</details>


## Properties

| Name | Type | Description |
|------|------|-------------|
| config | `\KWIO\GutenbergBlocks\Config` | Holds the configurated options.  |
| categories | `array` | Holds any defined custom categories.  |

## Methods
### __construct 




```php
Loader::__construct(string file)
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| file | `string` | The filename of the plugin or theme (`__FILE__`). |



### loadBlocks 
Registers the blocks on the server-side.



```php
Loader::loadBlocks(string dir, string namespace): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| dir | `string` | Blocks directory relative to the plugin or theme. |
| namespace | `string` | Namespace of the block classes (`__NAMESPACE__`). |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setBlockNamespace 
Customize the namepace of the blocks.

Defaults to plugin or theme name.

```php
Loader::setBlockNamespace(callable callback): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| callback | `callable` | Function which recieves current namespace as parameter and returns new namespace. |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setBlockWhitelist 
Defines an array of blocks that should be whitelisted.

Use `KWIO\GutenbergBlocks\Loader::CORE_BLOCK_WHITELIST` and merge it with your array to extend the current whitelist.

```php
Loader::setBlockWhitelist(array blockWhitelist): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| blockWhitelist | `array` | Array of allowed block slugs. |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setCategories 
Defines an array of custom block categories.

See [developer.wordpress.org](https://developer.wordpress.org/reference/hooks/block_categories_all/) for more.

```php
Loader::setCategories(array categories): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| categories | `array` | Array of category slugs. |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setDistDir 
Changes the path to the block assets dist folder.



```php
Loader::setDistDir(string distDir): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| distDir | `string` | Dist directory relative to the plugin or theme. |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setTranslationsPath 
Sets the path of the directory of your translation file (e.g. kwio-de_DE.json) to translate strings in your custom block in the admin.

Make sure that the text domain matches the configured namespace.

```php
Loader::setTranslationsPath(string path): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| path | `string` | Full path to the languages directory. |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setViewCachePath 




```php
Loader::setViewCachePath(string path): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| path | `string` |  |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### setViewClass 
Implement a custom template engine or choose one of the follwing already implemented engines:

- `KWIO\GutenbergBlocks\View\PhpView` (default)
- `KWIO\GutenbergBlocks\View\TwigView` (requires `twig/twig`)
- `KWIO\GutenbergBlocks\View\TimberView` (requires `timber/timber`)
- `KWIO\GutenbergBlocks\View\BladeOneView` (requires `eftec/bladeone`)

```php
Loader::setViewClass(string viewClass): \KWIO\GutenbergBlocks\Loader
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| viewClass | `string` | String of a class extending `AbstractView`. |


**Returns:** `\KWIO\GutenbergBlocks\Loader` 
### init 
Kick-starts the framework and sets up all the hooks. Should be the final method called.



```php
Loader::init(): void
```



**Returns:** `void` 
