---
layout: default
title: ViewInterface
parent: Reference
has_toc: false
---

# ViewInterface
{: .no_toc }





* Full name: `\KWIO\GutenbergBlocks\View\ViewInterface`


<details open markdown="block">
  <summary>
    Table of contents
  </summary>
  {: .text-delta }
1. TOC
{:toc}
</details>



## Methods
### render 
Renders view with data.



```php
ViewInterface::render(): string
```



**Returns:** `string` Rendered view.
### setData 
Adds data to view.



```php
ViewInterface::setData(array data): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| data | `array` | Data for view. |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
### setFile 
Sets view file to populate with data.



```php
ViewInterface::setFile(string file): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| file | `string` | Absolute path to view file. |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
