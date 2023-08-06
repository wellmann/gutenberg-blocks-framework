---
layout: default
title: BaseBlock
parent: Reference
has_toc: false
---

# BaseBlock
{: .no_toc }

Default class for every custom block.



* Full name: `\KWIO\GutenbergBlocks\BaseBlock`


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
| dirPath | `string` | Holds path to current block.  |
| baseClass | `string` | Holds base classname of current block  (e.g. `block-my-example`).  |
| name | `string` | Holds name (e.g. `kwio/my-example`.) of current block.  |
| slug | `string` | Holds slug (e.g. `my-example`.) of current block.  |
| config | `\KWIO\GutenbergBlocks\Config` | Holds the configurated options.  |
| tagAttr | `array` | Holds the HTML attributes that will be rendered on the block wrapper element. Is reset on every render. |
| data | `array` | Holds attributes saved in the editor plus any other variables added in the block class.  |
| renderCount | `int` | Holds how often the current block is rendered on the page.  |
| viewClass | `string` | Holds current view implementaion.  |

## Methods
### __construct 
Creates instance of current block type once per request.



```php
BaseBlock::__construct(string slug, string dirPath, \KWIO\GutenbergBlocks\Config config)
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| slug | `string` | Block slug without namespace (e.g. `my-example`). |
| dirPath | `string` | Path to current block. |
| config | `\KWIO\GutenbergBlocks\Config` | Configurated options. |



### getRenderCount 
Returns render count for current block type.



```php
BaseBlock::getRenderCount(): int
```



**Returns:** `int` Render count for current block.
### render 
Render callback passed to `register_block_type`.



```php
BaseBlock::render(array attributes, string content, \WP_Block|null block = null): string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| attributes | `array` | Holds attributes saved in the editor. |
| content | `string` | Holds content saved in the editor. |
| block | `\WP_Block\|null` | Holds additional block information like context. |


**Returns:** `string` Rendered HTML output of current block.
### setView 
Renders current block.



```php
BaseBlock::setView(string|null file, array data = [], string wrapperTagName = 'div'): string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| file | `string\|null` | Absolute path to current blocks view file. |
| data | `array` | Attributes saved in the editor plus any other variables added in the block class. |
| wrapperTagName | `string` | Block wrapper element tag name. |


**Returns:** `string` Rendered HTML output of current block.
### extractAttr <span class="label label-red">private</span>
Extract key–value pair from data passed to the blocks view
and optionally rename it for use as HTML attribute.



```php
BaseBlock::extractAttr(string attr, string newAttr = ''): null|string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| attr | `string` | Attribute name to extract. |
| newAttr | `string` | New attribute name to extract value into. |


**Returns:** `null|string` Attribute value.
### toSlug 
Convert KWIO\GutenbergBlocks\MyExample::class to my-example



```php
BaseBlock::toSlug(): string
```

* This method is **static**.

**Returns:** `string` 
