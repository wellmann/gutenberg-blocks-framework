---
layout: default
title: Block Class
nav_order: 15
---

# Block Class
{: .no_toc }

If your block doesn't require any PHP logic you don't have to create this file since the `KWIO\GutenbergBlocksFramework\BaseBlock` class will be used by default.

<details open markdown="block">
  <summary>
    Table of contents
  </summary>
  {: .text-delta }
1. TOC
{:toc}
</details>

```php
<?php

namespace KWIO\GutenbergBlocks;

use KWIO\GutenbergBlocksFramework\BaseBlock;

class MyBlock extends BaseBlock
{
    public function render(array $attributes, string $content): string
    {
        parent::render($attributes, $content);

        $customAttr = ... // Your custom logic here

        return $this->setView($this->dirPath . 'view.php', compact('customAttr'));
    }
}
```


## Constants

| Name              | Type    | Description                                                  |
|:------------------|:--------|:-------------------------------------------------------------|
| SHOW_ON_POST_TYPE | `array` | Array of post type slugs to which this block is visible only.|


## Properties

| Name         | Type              | Description                                                  |
|:-------------|:------------------|:-------------------------------------------------------------|
| dirPath      | `string`          | Path to the current block folder.|
| baseClass    | `string`          | Class name of the block (e.g. `block-my-block`).|
| pluginConfig | `PluginConfigDTO` | Object of configuration options passed to the `Loader` class.|
| tagAttr      | `array`           | Array of html attributes that will be rendered on the block wrapper element.|


## Methods

| Name              | Return type    | Description                                                  |
|:------------------|:--------|:-------------------------------------------------------------|
| render | `string` | Render callback passed to `register_block_type`. |
| setView | `string` | Rendered HTML output of the block. |


## Utility methods

| Name              | Return type    | Description                                                  |
|:------------------|:--------|:-------------------------------------------------------------|
| addClass | `void` | Additional classes to add to the block wrapper element. `%s` can be used as a placeholder for the base class. |
| addData | `void` | Add additional data via data attribute to the block wrapper element. |
| addJsonData | `void` | Add JSON data via script tag after the opening block wrapper tag. |
| addJs | `void` | Enqueue JS file only when block is rendered. |