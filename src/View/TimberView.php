<?php

namespace KWIO\GutenbergBlocksFramework\View;

use Timber\Post;
use Timber\Timber;
use Timber\Twig_Function;

class TimberView extends AbstractView
{
    use ViewUtilsTrait;

    public string $defaultView = 'view.twig';

    protected function renderWithView(): string
    {
        add_filter('timber/twig', function ($twig) {
            $twig->addFunction(new Twig_Function('bem', [$this, 'bem']));
            $twig->addFunction(new Twig_Function('renderBlock', [$this, 'renderBlock'], ['is_safe' => ['html']]));

            return $twig;
        });

        $this->data['isEditor'] = $this->isEditor();
        $this->data['post'] = new Post();
        $this->data['renderCount'] = $this->getRenderCount();

        return Timber::compile($this->locateView($this->file), $this->data);
    }
}