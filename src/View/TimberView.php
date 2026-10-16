<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\View;

use Timber\Timber;
use Twig\TwigFunction;

class TimberView extends AbstractView
{
    use ViewUtilsTrait;

    public static string $defaultView = 'view.twig';

    protected function renderWithView(): string
    {
        add_filter('timber/twig', function ($twig) {
            $twig->addFunction(new TwigFunction('bem', [$this, 'bem']));
            $twig->addFunction(new TwigFunction('renderBlock', [$this, 'renderBlock'], ['is_safe' => ['html']]));

            return $twig;
        });

        $this->data['isEditor'] = $this->isEditor();
        $this->data['post'] = Timber::get_post();
        $this->data['renderCount'] = $this->getRenderCount();

        return Timber::compile($this->locateView($this->file), $this->data);
    }
}