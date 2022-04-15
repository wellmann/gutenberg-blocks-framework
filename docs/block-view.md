---
layout: default
title: Block View
nav_order: 20
---

# Block View
{: .no_toc }

The view file is return by the `render` function of the block class and rendered between the block wrapper element.

<details open markdown="block">
  <summary>
    Table of contents
  </summary>
  {: .text-delta }
1. TOC
{:toc}
</details>

## Pure PHP view

PHP:
```php
<div class="<?= esc_attr($this->bem('element', ['modifier', $this->getRenderCount()])) ?>">
  <?php if (!empty($content)): ?>
    <?= esc_html($content) ?>
  <?php endif; ?>
</div>
```

Rendered result:
```html
<div class="block-my-block">
  <div class="block-my-block__element block-my-block__element--modifier block-my-block__element--1">
    <!-- block content -->
  </div>
</div>
```

### Utility methods

| Name              | Return type    | Description                                                  |
|:------------------|:--------|:-------------------------------------------------------------|
| bem | `string` | Generate class names according to the BEM methodology. |
| isEditor | `bool` | Check if view is rendered in the block editor via `@wordpress/server-side-render`. |
| getPost | `WP_Post` | Get current `WP_Post` object. |
| getRenderCount | `int` | If you have the same block multiple times on a page this function allows you to generate a unique class name or id. |
| renderBlockClass | `string` | Renders a block by passing the fully qualified class name. |
| renderBlock | `string` | Renders a block by passing the blocks name without namespace. |


## Twig view

```twig
{% raw %}<div class="{{ bem('element', ['modifier', renderCount])|e('html_attr') }}">
  {{ content }}
</div>{% endraw %}
```

Utility methods:
* [bem](#utility-methods)
* [renderBlock](#utility-methods)
* [__](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}
* [_x](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}
* [_n](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}
* [_nx](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}

Escapers:

* [wp_kses_post](https://developer.wordpress.org/reference/functions/wp_kses_post/){:target="_blank"}

Additional data:
* [isEditor](#utility-methods)
* [post](https://developer.wordpress.org/reference/classes/wp_post/){:target="_blank"}
* [renderCount](#utility-methods)

You can create an instance of the `KWIO\GutenbergBlocksFramework\View\TwigView` class and pass a custom TwigExtension to the constructor if you need to add additional functions or filters.

## Timber view

```twig
{% raw %}<div class="{{ bem('element', ['modifier', renderCount]) }}">
  {{ content }}
</div>{% endraw %}
```

Utility methods:
* [bem](#utility-methods)
* [renderBlock](#utility-methods)

Additional data:
* [isEditor](#utility-methods)
* [post](https://timber.github.io/docs/reference/timber-post/){:target="_blank"}
* [renderCount](#utility-methods)


## Creating a custom  view

You can create your own custom view loader with your favorite template engine by creating a new class which extends the `KWIO\GutenbergBlocksFramework\View\AbstractView` class and pass an instance to the `KWIO\GutenbergBlocksFramework\Loader::setViewClass` method.