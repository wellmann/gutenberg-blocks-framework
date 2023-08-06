[![Tests](https://github.com/wellmann/gutenberg-blocks-framework/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/wellmann/gutenberg-blocks-framework/actions/workflows/tests.yml)
[![Docs](https://github.com/wellmann/gutenberg-blocks-framework/actions/workflows/docs.yml/badge.svg?branch=master)](https://github.com/wellmann/gutenberg-blocks-framework/actions/workflows/docs.yml)
[![pages-build-deployment](https://github.com/wellmann/gutenberg-blocks-framework/actions/workflows/pages/pages-build-deployment/badge.svg?branch=gh-pages)](https://github.com/wellmann/gutenberg-blocks-framework/actions/workflows/pages/pages-build-deployment)

# 🧱 Gutenberg Blocks Framework

Library to load custom Gutenberg blocks in a WordPress plugin or theme.

Since this library is solely taking care of registering and setting up blocks on the server side it is built to work with the following packages:   

* [kwio/gutenberg-blocks-components](https://github.com/wellmann/gutenberg-blocks-components)
* [wellmann/create-block](https://github.com/wellmann/create-block)

Example projects:
 * https://github.com/wellmann/kwio-gutenberg-blocks (as a seperate plugin)
 * https://github.com/wellmann/kwio-wp-theme (as part of a theme)

## Features

* [dynamic](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) blocks (pure PHP, Twig or Blade views)
* enhance output of core blocks
* optimized for performance (critical CSS)
* whitelist of blocks
* easy integration of i18n
* view utility functions (e.g. to generete BEM CSS classes)

Read more: https://wellmann.github.io/gutenberg-blocks-framework/#/installation