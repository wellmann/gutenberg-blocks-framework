# Gutenberg Blocks Framework 🧱

Library to load custom Gutenberg blocks in a WordPress plugin or theme.

Since this library is solely taking care of registering and setting up blocks on the server side it is built to work with the following packages:   

* [kwio/gutenberg-blocks-components](https://github.com/wellmann/gutenberg-blocks-components)
* [wellmann/create-block](https://github.com/wellmann/create-block)

Example projects:
 * https://github.com/wellmann/kwio-gutenberg-blocks (as a seperate plugin)
 * ~~https://github.com/wellmann/kwio-gutenberg-theme (as part of a theme)~~ (coming soon)

## Features

* customizable whitelist of selected core blocks
* enhance output of core blocks
* optimized for performance (critical CSS)
* all blocks are [dynamic](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) (pure PHP or Twig views)
* easy integration of i18n
* view utility functions
* supports BEM methodology

Read more: https://wellmann.github.io/gutenberg-blocks-framework/installation.html