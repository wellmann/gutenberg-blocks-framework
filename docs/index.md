---
layout: default
title: Intro
nav_order: 0
---

# Gutenberg Blocks Framework 🧱
{: .fs-9 }

Library to load custom Gutenberg blocks in a WordPress plugin or theme.
{: .fs-6 .fw-300 }

[Get started now](installation.html){: .btn .btn-primary .fs-5 .mb-4 .mb-md-0 .mr-2 } [View it on GitHub](https://github.com/wellmann/gutenberg-blocks-framework){:target="_blank"}{: .btn .fs-5 .mb-4 .mb-md-0 }

---

Since this library is solely taking care of registering and setting up blocks on the server side it is built to work with the following packages:  

* [kwio/gutenberg-blocks-components](https://github.com/wellmann/gutenberg-blocks-components)
* [wellmann/create-block](https://github.com/wellmann/create-block)

Example projects:
* [https://github.com/wellmann/kwio-gutenberg-blocks](https://github.com/wellmann/kwio-gutenberg-blocks) (as a seperate plugin)
* ~~[https://github.com/wellmann/kwio-gutenberg-blocks-theme](https://github.com/wellmann/kwio-gutenberg-blocks-theme) (as part of a theme)~~ <small style="opacity:.5;">(coming soon)</small>

## Features

* customizable whitelist of selected core blocks
* enhance output of core blocks
* loading of critical and non-critical CSS
* use pure PHP or Twig views (all blocks are dynamic)
* easy integration of editor view translations
* helper functions to generate class names according to the BEM methodology

Read more: [Installation](/installation.html)