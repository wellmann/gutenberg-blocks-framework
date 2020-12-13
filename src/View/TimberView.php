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
        add_filter('timber/twig', function (Environment $twig): Environment {
            $twig->addFunction(new Twig_Function('bem', [$this, 'bem']));
            $twig->addFunction(new Twig_Function('get_post', [$this, 'getTimberPost']));

            return $twig;
        });

        return Timber::compile($this->file, $this->data);
    }

    public function getTimberPost(): Post
    {
        return new Post();
    }
}