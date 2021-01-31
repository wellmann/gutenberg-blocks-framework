<?php

namespace KWIO\GutenbergBlocksFramework\View;

use Timber\Post;
use Timber\Timber;
use Timber\Twig_Function;
use Twig\Environment;

class TimberView extends AbstractView
{
    public string $defaultView = 'view.twig';

    public function render(): string
    {
        add_filter('timber/twig', function ($twig) {
            $twig->addFunction(new Twig_Function('bem', [$this, 'bem']));
            $twig->addFunction(new Twig_Function('post', [$this, 'getTimberPost']));
            $twig->addFunction(new Twig_Function('render_count', [$this, 'getRenderCount']));

            return $twig;
        });

        return Timber::compile($this->file, $this->data);
    }

    public function getTimberPost(): Post
    {
        return new Post();
    }
}