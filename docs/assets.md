# Assets

## Styles & scripts

The `KWIO\GutenbergBlocks\AssetCollector` will look into the dist directory or directory defined via `KWIO\GutenbergBlocks\Loader::setDistDir` method to find the follwing files and enqueue them:

* [blocks.css - contains non-critical styles for editor and frontend](#defer-loading-of-non-critical-css)
* blocks.js - JavaScript bundle for the frontend
* critical.css - styles are directly embedded into the page for editor and frontend
* editor.css - extra editor styles
* editor.js - where all your blocks are registered on the client side

Use the follwing package to compile the files according to this schema:

* [kwio/gutenberg-blocks-components](https://github.com/wellmann/gutenberg-blocks-components)


### Defer loading of non-critical CSS

To load the non-critical CSS (blocks.css) in the frontend you have to include the following filter in your functions.php.

```php
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