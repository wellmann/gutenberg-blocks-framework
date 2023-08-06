---
layout: default
title: AbstractView
parent: Reference
has_toc: false
---

# AbstractView
{: .no_toc }





* Full name: `\KWIO\GutenbergBlocks\View\AbstractView`
* This class implements: `\KWIO\GutenbergBlocks\View\ViewInterface`


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
| defaultView | `string` | Holds value of default view file name.  |
| baseClass | `string` | Holds base classname of current block  (e.g. `block-my-example`).  |
| cachePath | `string\|null` | Holds absolute path to a custom cache directory for twig or blade views.  |
| namespace | `string` | The theme or plugin name.  |
| data | `array` | Holds attributes saved in the editor plus any other variables added in the block class.  |
| file | `string\|null` | Holds absolute path to view file.  |
| wrapperDiv | `string` | Sets the view files wrapper div element and attributes.  |

## Methods
### renderWithView 
Extract data variables for use in view file.



```php
AbstractView::renderWithView(): string
```



**Returns:** `string` Rendered view fil with wrapper elment.
### setData 
Adds data to view.



```php
AbstractView::setData(array data): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| data | `array` | Data for view. |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
### setFile 
Sets view file to populate with data.



```php
AbstractView::setFile(?string file): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| file | `?string` | Absolute path to view file. |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
### setCachePath 




```php
AbstractView::setCachePath(?string path): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| path | `?string` |  |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
### render 
Renders view with data.



```php
AbstractView::render(): string
```



**Returns:** `string` Rendered view.
### locateView 
Allows block view to be overridden in child theme.



```php
AbstractView::locateView(string filePath): string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| filePath | `string` | Absolute path to view file. |


**Returns:** `string` Located view file.
### buildTagAttrString <span class="label label-red">private</span>
Converts key-value pairs to string of HTML attributes.



```php
AbstractView::buildTagAttrString(array array): string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| array | `array` | Array of attributes. |


**Returns:** `string` String of HTML attributes.

**See:**

* `\KWIO\GutenbergBlocks\View\AbstractView::setData`  

