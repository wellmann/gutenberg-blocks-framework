<?php

namespace KWIO\GutenbergBlocksFramework\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigViewExtension extends AbstractExtension
{
    private TwigView $twigView;

    public function __construct(TwigView $twigView)
    {
        $this->twigView = $twigView;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('__', '__'),
            new TwigFunction('_x', '_x'),
            new TwigFunction('_n', '_n'),
            new TwigFunction('_nx', '_nx'),
            new TwigFunction('bem', [$this->twigView, 'bem']),
            new TwigFunction('renderBlock', [$this->twigView, '_renderBlockInternal'], ['is_safe' => ['html']])
        ];
    }
}