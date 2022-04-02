---
layout: default
title: Setup
nav_order: 10
---

# Setup
{: .no_toc }

Add the following lines to the plugins bootstrap file or the themes functions.php:

```php
$frameworkLoader = new KWIO\GutenbergBlocksFramework\Loader(__FILE__);
$frameworkLoader
    ->loadBlocks('src/', __NAMESPACE__) // __NAMESPACE__ should match the namespace of your block classes.
    ->init();
```

<details open markdown="block">
  <summary>
    Table of contents
  </summary>
  {: .text-delta }
1. TOC
{:toc}
</details>

## Optional methods

### setBlockWhitelist(array $blockWhitelist)

Pass a custom array of blocks that should be whitelisted or use `KWIO\GutenbergBlocksFramework\Loader::CORE_BLOCK_WHITELIST` and merge it with your array to extend the current whitelist.

### setDistDir(string $distDir)

Pass a directory relative to the plugin dir path to customize the block assets dist folder.

### setTranslationsPath(string $path)

Pass the path of the directory of your translation file (e.g. kwio-de_DE.json) to translate strings in your custom block in the admin.  
Make sure that the text domain matches this plugins prefix.

### setViewClass(ViewInterface $viewClass)

Implement a custom template engine or choose one of the follwing already implemented engines:

* `KWIO\GutenbergBlocksFramework\View\PhpView` (default)
* `KWIO\GutenbergBlocksFramework\View\TwigView` (requires `twig/twig`)
* `KWIO\GutenbergBlocksFramework\View\TimberView` (requires `timber/timber`)

## File structure

The subfolder name will represent the block slug and the class should be named accordingly in PascalCase (though it is still supported to name it `block.php` for backwards compatibility).

```
src 
│
└───my-block
│   │   block.js
│   │   editor.scss // optional
│   │   meta.json
│   │   MyBlock.php // optional
│   │   style.scss // or style.critical.scss
│   │   view.php
│   
└───my-block-example
│   │   block.js
│   │   editor.scss // optional
│   │   meta.json
│   │   MyBlockExample.php // optional
│   │   style.scss // or style.critical.scss
│   │   view.php
│   ...
```