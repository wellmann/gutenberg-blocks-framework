# Gutenberg Blocks Framework

Library to load custom Gutenberg blocks.

## Install

Make sure you have the following repository added to your composer.json file.

```
{
  "repositories": [{
    "type": "composer",
    "url": "https://ce-kw.github.io/satis/"
  }],
  "minimum-stability": "dev",
  "prefer-stable": true
}
```

Then run `composer require kwio/gutenberg-blocks-framework`.

## Setup

```
$frameworkLoader = new \KWIO\GutenbergBlocksFramework\Loader(__FILE__);
$frameworkLoader
    ->loadBlocks('src/', __NAMESPACE__)
    ->init();
```

## Optional methods

### setBlockWhitelist(array $blockWhitelist)

Pass a custom array of blocks that should be whitelisted or use `\KWIO\GutenbergBlocksFramework\Loader::CORE_BLOCK_WHITELIST` and merge it with your array to extend the current whitelist.

### setDistDir(string $distDir)

Pass a directory relative to the plugin dir path to customize the block assets dist folder.

### setViewClass(ViewInterface $viewClass)

Implement custom template engine.

* `KWIO\GutenbergBlocksFramework\View\PhpView` (default)
* `KWIO\GutenbergBlocksFramework\View\TwigView` (requires `twig/twig`)
* `KWIO\GutenbergBlocksFramework\View\TimberView` (requires `timber/timber`)