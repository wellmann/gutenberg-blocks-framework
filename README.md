# Gutenberg Blocks Framework

Library to load custom Gutenberg blocks in a WordPress plugin or theme.

Built to work with the following packages:  

* [kwio/gutenberg-blocks-components](https://github.com/wellmann/gutenberg-blocks-components)
* [wellmann/create-block](https://github.com/wellmann/create-block)

Example project: https://github.com/wellmann/kwio-gutenberg-blocks

## Features

* whitelist of selected core blocks
* overwrite output of core blocks
* loading of critical and non-critical CSS
* use pure PHP or Twig views
* load translations from *.po files in the editor view
* customize the folder where the framework is looking for the compiled block asset files
* class names follow the BEM pattern

## Install

Make sure you have the following repository added to your composer.json file in your plugin or theme.

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

Add the following lines to the plugins bootstrap file or the themes functions.php.

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

### setTranslationsPath(string $path)

Pass the path of the directory of your translation file (e.g. kwio-de_DE.json) to translate strings in your custom block in the admin.  
Make sure that the domain matches this plugins prefix.

### setViewClass(ViewInterface $viewClass)

Implement a custom template engine.

* `KWIO\GutenbergBlocksFramework\View\PhpView` (default)
* `KWIO\GutenbergBlocksFramework\View\TwigView` (requires `twig/twig`)
* `KWIO\GutenbergBlocksFramework\View\TimberView` (requires `timber/timber`)

## Defer loading of non-critical CSS

To load the non-critical CSS you have to include the following filter in your functions.php.

```
add_filter('style_loader_tag', function (string $html, string $handle, string $href, string $media): string {
    if ($media !== 'nonblocking') {
        return $html;
    }

    $originalHtml = str_replace('nonblocking', 'all', trim($html));
    $link = "<link rel='stylesheet' id='{$handle}-css' href='{$href}' type='text/css' media='{$media}' onload='this.onload=null;this.media=\"all\"'>\n";
    $link .= "<noscript>{$originalHtml}</noscript>\n";

    return $link;
}, 10, 4);
```