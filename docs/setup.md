# Setup

Add the following lines to the plugins bootstrap file or the themes functions.php:

```php
$blocksLoader = new KWIO\GutenbergBlocks\Loader(__FILE__);
$blocksLoader
    ->loadBlocks('src/', __NAMESPACE__) // __NAMESPACE__ should match the namespace of your block classes.
    ->init();
```

[Loader class reference](reference/Loader)

## File structure

The subfolder name will represent the block slug and the class should be named accordingly in PascalCase (though it is still supported to name it `block.php` for backwards compatibility).

```
blocks 
│
└───my-block
│   │   block.js
│   │   editor.scss // optional
│   │   block.json
│   │   MyBlock.php // optional
│   │   style.scss // or style.critical.scss
│   │   view.php
│   
└───my-block-example
│   │   block.js
│   │   editor.scss // optional
│   │   block.json
│   │   MyBlockExample.php // optional
│   │   style.scss // or style.critical.scss
│   │   view.php
│   ...
```