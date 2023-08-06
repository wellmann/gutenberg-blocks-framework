# Block View

The view file is return by the `render` function of the block class and rendered between the block wrapper element.

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

[ViewUtilsTrait reference](reference/ViewUtilsTrait)

## Twig view

`composer require twig/twig`

```twig
<div class="{{ bem('element', ['modifier', renderCount])|e('html_attr') }}">
  {{ content }}
</div>
```

Utility methods:
* [bem](reference/ViewUtilsTrait#bem)
* [renderBlock](reference/ViewUtilsTrait#renderblock)
* [__](https://developer.wordpress.org/themes/functionality/internationalization/)
* [_x](https://developer.wordpress.org/themes/functionality/internationalization/)
* [_n](https://developer.wordpress.org/themes/functionality/internationalization/)
* [_nx](https://developer.wordpress.org/themes/functionality/internationalization/)

Escapers:

* [wp_kses_post](https://developer.wordpress.org/reference/functions/wp_kses_post/)

Additional data:
* [isEditor](reference/ViewUtilsTrait#iseditor)
* [post](https://developer.wordpress.org/reference/classes/wp_post/)
* [renderCount](reference/ViewUtilsTrait#getrendercount)

You can create an instance of the `KWIO\GutenbergBlocks\View\TwigView` class and pass a custom TwigExtension to the constructor if you need to add additional functions or filters.

## Timber view

`composer require timber/timber`

```twig
<div class="{{ bem('element', ['modifier', renderCount]) }}">
  {{ content }}
</div>
```

Utility methods:
* [bem](reference/ViewUtilsTrait#bem)
* [renderBlock](reference/ViewUtilsTrait#renderblock)

Additional data:
* [isEditor](reference/ViewUtilsTrait#iseditor)
* [post](https://timber.github.io/docs/reference/timber-post/)
* [renderCount](reference/ViewUtilsTrait#getrendercount)

## Blade view

`composer require eftec/bladeone`

```blade
<div class="@bem('element', ['modifier', $renderCount])">
  {{ $content }}
</div>
```

Utility methods:
* [@bem](reference/ViewUtilsTrait#bem)
* [@renderBlock](reference/ViewUtilsTrait#renderblock)

Additional data:
* [$isEditor](reference/ViewUtilsTrait#iseditor)
* [$post](https://timber.github.io/docs/reference/timber-post/)
* [$renderCount](reference/ViewUtilsTrait#getrendercount)

## Creating a custom  view

You can create your own custom view loader with your favorite template engine by creating a new class which extends the `KWIO\GutenbergBlocks\View\AbstractView` class and pass the class name to the `KWIO\GutenbergBlocks\Loader::setViewClass` method.