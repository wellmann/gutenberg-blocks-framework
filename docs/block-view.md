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

[ViewUtilsTrait reference](/reference/ViewUtilsTrait.html)

## Twig view

```twig
{% raw %}<div class="{{ bem('element', ['modifier', renderCount])|e('html_attr') }}">
  {{ content }}
</div>{% endraw %}
```

Utility methods:
* [bem](/reference/ViewUtilsTrait.html#bem)
* [renderBlock](/reference/ViewUtilsTrait.html#renderblock)
* [__](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}
* [_x](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}
* [_n](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}
* [_nx](https://developer.wordpress.org/themes/functionality/internationalization/){:target="_blank"}

Escapers:

* [wp_kses_post](https://developer.wordpress.org/reference/functions/wp_kses_post/){:target="_blank"}

Additional data:
* [isEditor](/reference/ViewUtilsTrait.html#iseditor)
* [post](https://developer.wordpress.org/reference/classes/wp_post/){:target="_blank"}
* [renderCount](/reference/ViewUtilsTrait.html#getrendercount)

You can create an instance of the `KWIO\GutenbergBlocksFramework\View\TwigView` class and pass a custom TwigExtension to the constructor if you need to add additional functions or filters.

## Timber view

```twig
{% raw %}<div class="{{ bem('element', ['modifier', renderCount]) }}">
  {{ content }}
</div>{% endraw %}
```

Utility methods:
* [bem](/reference/ViewUtilsTrait.html#bem)
* [renderBlock](/reference/ViewUtilsTrait.html#renderblock)

Additional data:
* [isEditor](/reference/ViewUtilsTrait.html#iseditor)
* [post](https://timber.github.io/docs/reference/timber-post/){:target="_blank"}
* [renderCount](/reference/ViewUtilsTrait.html#getrendercount)


## Creating a custom  view

You can create your own custom view loader with your favorite template engine by creating a new class which extends the `KWIO\GutenbergBlocksFramework\View\AbstractView` class and pass the class name to the `KWIO\GutenbergBlocksFramework\Loader::setViewClass` method.