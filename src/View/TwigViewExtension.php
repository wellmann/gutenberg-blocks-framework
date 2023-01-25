<?php

namespace KWIO\GutenbergBlocks\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigViewExtension extends AbstractExtension
{
    /**
     * Holds instance of current view.
     *
     * @var TwigView
     */
    private TwigView $twigView;

    /**
     * @param TwigView $twigView Instance of current view.
     */
    public function __construct(TwigView $twigView)
    {
        $this->twigView = $twigView;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('__', '__'),
            new TwigFunction('_x', '_x'),
            new TwigFunction('_n', '_n'),
            new TwigFunction('_nx', '_nx'),
            new TwigFunction('bem', [$this->twigView, 'bem']),
            new TwigFunction('renderBlock', [$this->twigView, 'renderBlock'], ['is_safe' => ['html']])
        ];
    }
}